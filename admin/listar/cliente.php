<?php
  //verificar se não está logado
  if ( !isset ( $_SESSION["hqs"]["id"] ) ){
    exit;
  }
?>
<div class="container">
	<h1 class="float-left">Listar Clientes</h1>
	<div class="float-right">
		<a href="cadastro/cliente" class="btn btn-success">Novo Registro</a>
		<a href="listar/cliente" class="btn btn-info">Listar Registros</a>
	</div>

	<div class="clearfix"></div>

	<table class="table table-striped table-bordered table-hover" id="tabela">
		<thead>
			<tr>
                <td>ID</td>
                <td>Foto</td>
                <td>Nome</td>
				<td>Data de Nascimento</td>
				<td>CPF</td>
				<td>Endereço</td>
                <td>Bairro</td>
                <td>Cidade</td>
                <td>Estado</td>
                <td>Email</td>
                <td>Celular</td>
                <td>Telefone</td> 
			</tr>
		</thead>
		<tbody>
			<?php
                $sql = "select c.id, c.foto, c.nome, date_format(c.datanascimento, '%d/%m/%Y') dt, c.cpf, c.endereco, c.bairro, c.cidade, c.estado, c.email, c.celular, c.telefone, ci.cidade cidade from cliente c INNER JOIN cidade ci on (ci.id = c_id) order by c.nome";

//"select q.id, q.titulo, q.capa, q.valor, q.numero, date_format(q.data, '%d/%m/%Y') dt, 
               // e.nome editora from quadrinho q INNER join editora e on (e.id = q.editora_id) order by q.titulo";



                $consulta = $pdo->prepare($sql);
                $consulta->execute();
                while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {

                    //recuperar os dados
                    $id             = $dados->id;
                    $foto           = $dados->foto;
                    $nome           = $dados->nome;
                    //$datanascimento = $dados->datanascimento;
                    $dt             = $dados->dt;
                    //$valor        = number_format($dados->valor,2,",",".");
                    $cpf            = $dados->cpf;
                    $endereco       = $dados->endereco;
                    $bairro         = $dados->bairro;
                    $cidade         = $dados->cidade;
                    $estado         = $dados->estado;
                    $email          = $dados->email;
                    $celular        = $dados->celular;
                    $telefone       = $dados->telefone;
                    

                    $imagem = "../fotos/".$foto."p.jpg";

                    echo "<tr>
                            <td>$id</td>
                            <td>
                                <img src='$imagem' alt='$titulo' width='50px'>
                            </td>
                            <td>$nome</td>
                            <td>$dt</td>
                            <td>$cpf</td>
                            <td>$endereco</td>
                            <td>$bairro</td>
                            <td>$cidade</td>
                            <td>$estado</td>
                            <td>$email</td>
                            <td>$celular</td>
                            <td>$telefone</td>
                            <td>
                              <a href='cadastro/cliente/$id' class='btn btn-success btn-sm'>
                                <i class='fas fa-edit'></i>
                              </a>
                              <a href='javascript:excluir($id)' class='btn btn-danger btn-sm'>
                                <i class='fas fa-trash'></i>
                              </a>
                            </td>
                        </tr>";
                }
            
            ?>
        </tbody>
    </table>
    <script type="text/javascript">
        function excluir(id){
          if ( confirm("deseja realmente excluir este registro?") ){
            location.href='excluir/cliente/'+id;
          }
        }
    //adicionar o dataTable a minha tabela
		$(document).ready(function(){
		$('#tabela').DataTable({
			"language": {
				"lengthMenu": "Mostrando _MENU_ Registros por Pagina",
				"zeroRecords": "Nenhum Registro Encontrado",
				"info": "Mostrando Paginas de  _PAGE_ de _PAGES_",
				"infoEmpty": "No records available",
				"infoFiltered": "(filtered from _MAX_ total records)",
				"search": "buscar"
				
			}
		} );
	})
	</script>
</div>
		