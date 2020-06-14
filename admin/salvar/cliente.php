<?php
  //verificar se não está logado
  if ( !isset ( $_SESSION["hqs"]["id"] ) ){
    exit;
  }

  //verificar se existem dados no POST
  if ( $_POST ) {
    
    //include "functions.php";
    include "config/conexao.php";

    //$id = $titulo = $data = $numero = $valor = $resumo = $tipo_id = $editora_id = "";
    $id = $nome = $cpfc = $datanascimento = $email = $senha = $cep = $endereco = $complemento = $bairro = $cidade_id = $foto = $telefone = $celular = $nome_cidade = $estado = "";

    foreach ($_POST as $key => $value) {
        $$key = trim ( $value );
    }

    if ( empty ( $nome ) ) {
        echo "<script>alert('Preencha o Nome');history.back();</script>";
        exit;
    } else if ( empty ( $cidade_id ) ) {
        echo "<script>alert('alguma coisa acontece aki');history.back();</script>";
        exit;
    }

    //iniciar uma transação
    //tudo oq é feito daqui para baixo (beginTransaction) só vai efetivar a alteração
    // no banco depois de der um commit ($pdo->commit();)
    $pdo->beginTransaction();

    //formatando os valores
    //$datanascimento = formatar( $datanascimento );
    //$numero = retirar( $numero );
    //$valor = formatarValor( $valor );

    $arquivo = time()."-".$_SESSION["hqs"]["id"];

    if ( empty ( $id ) ) {
        //inserir
        $sql = "insert into cliente (nome, cpfc, datanascimento, email, senha, cep, endereco, complemento, bairro, cidade_id, foto, telefone, celular, nome_cidade, estado)
      values( :nome, :cpf, :datanascimento, :email, :senha, :cep, :endereco, :complemento, :bairro, :cidade_id, :foto, :telefone, :celular, :nome_cidade, :estado )";

        $consulta = $pdo->prepare($sql);
        $consulta->bindParam(1, $nome);
        $consulta->bindParam(2, $cpfc);
        $consulta->bindParam(3, $datanascimento);
        $consulta->bindParam(4, $email);
        $consulta->bindParam(5, $senha);
        $consulta->bindParam(6, $cep);
        $consulta->bindParam(7, $endereco);
        $consulta->bindParam(8, $complemento);
        $consulta->bindParam(9, $bairro);
        $consulta->bindParam(10, $cidade_id);
        $consulta->bindParam(11, $foto);
        $consulta->bindParam(12, $telefone);
        $consulta->bindParam(13, $celular);
        $consulta->bindParam(14, $nome_cidade);
        $consulta->bindParam(15, $estado);

    }else {
        //editar - update 

        //qual arquivo ira ser gfravado
        if ( !empty ( $_FILES["foto"]["name"] ) ) {
            $foto = $arquivo;

            $sql = "update quadrinho set nome = :nome,
            cpfc = :cpfc, datanascimento = :datanascimento, email = :email,
            foto = :foto, senha = :senha, cep = :cep, endereco = :endereco, complemento = :complemento,
             bairro = :bairro, cidade_id = :cidade_id, telefone = :telefone, celular = :celular, nome_cidade = :nome_cidade, estado = :estado 
            where id = :id limit 1 ";
            $consulta = $pdo->prepare($sql);
            $consulta->bindParam(":nome",$nome);
            $consulta->bindParam(":cpfc",$cpfc);
            $consulta->bindParam(":datanascimento",$datanascimento);
            $consulta->bindParam(":email",$email);
            $consulta->bindParam(":foto",$foto);
            $consulta->bindParam(":senha",$senha);
            $consulta->bindParam(":cep",$cep);
            $consulta->bindParam(":endereco",$endereco);
            $consulta->bindParam(":complemento",$complemento);
            $consulta->bindParam(":bairro",$bairro);
            $consulta->bindParam(":cidade_id",$cidade_id);
            $consulta->bindParam(":telefone",$telefone);
            $consulta->bindParam(":celular",$celular);
            $consulta->bindParam(":nome_cidade",$nome_cidade);
            $consulta->bindParam(":estado",$estado);
            $consulta->bindParam(":id",$id);
        }
    }

    //executar o sql
    if ( $consulta->execute() ) {
        
        //verificar se o arquivo nao esta sendo enviadop
        //capa deve estar vazia e o id nao pode estar vazio - editando 
        if ( ( empty ( $_FILES["foto"]["type"] ) ) and ( !empty( $id ) ) ) {
            //gravar no banco - se tudo deu certo
           $pdo->commit();
            echo "<script>alert('Registro salvo');location.href='listar/cliente';</script>";
            exit;
        }

        //verificar se o tipo de imagem é JPG
        if ( $_FILES["foto"]["type"] != "imagem/jpeg" ) {
            echo "<script>alert('Selecione uma imagem JPG Valida!');history.back();</script>";
            exit;
        }

        //copiar a imagem para o servidor
        if(move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/".$_FILES["foto"]["name"]) ) {

            //redimencionar imagens
            $pastaFotos = "../fotos/";
            $imagem = $_FILES["foto"]["name"];
            $nome = $arquivo;
            redimensionarImagem($pastaFotos,$imagem,$nome);

            //gravar no banco - se tudo deu certo
            $pdo->commit();
            echo "<script>alert('Registro salvo');location.href='listar/cliente';</script>";
            exit;

        }
        //erro ao gravar
        echo "<script>alert('Erro ao salvar ou enviar arquivo para o servidor');history.back();</script>";
        exit;
    }
      exit;
  }

echo "<p class='alert alert-danger'>Requisição Invalida!</p>";