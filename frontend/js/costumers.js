var myTable = null

$(function() { //quando a página carrega
	loadTableCostumers()

	//Função para o botão que cria novos Clientes
	$('#btnAddCostumer').click(function(){
		limparCampos();

		clearErrors();
	});

	let numResp = 0;

	$('#btnResp').click(function(){

		$('#camposResp').append(`<input type="text" id='resp_${numResp}'>`)
	})
	 
	/* Cadastrar ou Editar Clientes --------------------------------------------------------------*/	
	$("#btnSaveCostumer").click(function(e) { //quando enviar o formulário de Clientes

		e.preventDefault();

		$("#telefone").unmask();

		let form = $('#formCostumer');

		//A interface FormData fornece uma maneira fácil de construir um conjunto de pares chave/valor representando campos
	 // de um elemento form
		let formData = new FormData(form[0]);

		idCostumer = $('#id').val()

		if((idCostumer == 0) || (idCostumer == undefined)){ //Se for para cadastrar --------------------------------------------------

			$.ajax({
				type: "POST",
				url: 'http://localhost/api/v1/clientes/create',
				data: formData,
				contentType: false,
				processData: false,

				//Conforme indica a documentação, a opção beforeSend deve ser usada para executar efeitos (ou mudar as
				// opções da operação), antes da requisição ser efetuada.
				beforeSend: function() {
					clearErrors();
					//$("#btnSaveCostumer").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
					console.log(response)
					if (response.error) {
						console.log('erro ao cadastrar novo Cliente!')
						//response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
							'error'
						)
	
						if(response.error_list){
							
							showErrorsModal(response.error_list)
						}
						
					} else {
						$('#costumerModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Cliente cadastrado!',
							'success'
							)
	
						loadTableCostumers();
						$('#formCostumer').trigger("reset");
						
					}
					
				},
				error: function (response) {
					$('#formCostumer').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o cliente: ' + idCostumer)

			$.ajax({
				type: "POST", // or PUT for update
				url: `http://localhost/api/v1/clientes/${idCostumer}`, //rota para editar
				data: formData,
				//data: JSON.stringify(formData),
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					//$("#btnSaveCostumer").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (response.error) {
						console.log('erro ao editar cliente!')

						//response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
							'error'
						);

						if(response['error_list']){
							
							showErrorsModal(response['error_list'])
						}

					} else {
						$('#costumerModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Cliente atualizado!',
							'success'
						);

						loadTableCostumers();
						$('#formCostumer').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#CostumerModal').modal('hide');
					$('#formCostumer').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	
		
		return false;
	});

});

function mascaraTelefone(value){
    value = value.replace(/\D/g,"");                  //Remove tudo o que não é dígito
    value = value.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    value = value.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    value = value.substr(0, 15);
    return value;
}

function loadTableCostumers(){ //carrega a tabela de Clientes depois de umas alteração


	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

		//carrega a tabela de Costumers
	//function 
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "http://localhost/api/v1/clientes/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
			dataSrc: function (json) {
				
				rows = [];

				json.data.forEach(element => {
					//console.log(element)

					row = []

					//Essa variavel você pode apresentar
					var telFormatado = mascaraTelefone(element.telefone);

					//row['id'] = element.id
					
					row['nome'] = element.nome
					row['dtNasc'] = formatDate(element.dtNasc)
					row['RG'] = element.RG
					row['CPF'] = element.CPF
					row['telefone'] = telFormatado
					row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					onclick='loadCostumer(${element.id});'>
						<i class='fas fa-bars sm'></i>
					</button>
					<button type='button' title='excluir' onclick='deleteCostumer(${element.id});'
						class='btn btn-danger btnDelete'>
						<i class='fas fa-trash'></i>
					</button>`

					rows.push(row)
				
				});
				
				return rows;
			},


		},
		"columns": [
			{ "data": "nome" },
			{ "data": "dtNasc" },
			{ "data": "RG" },
			{ "data": "CPF" },
			{"data": "telefone"},
			{ "data": "options" },
			        
		],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});


}


//detalhes do cliente
function loadCostumer(idCliente) { //carrega todos os campos do modal referente ao cliente escolhido
	
	clearErrors();

	$('#modalTitle').html('Detalhes do Cliente')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveCostumer').hide();
	$('#btnUpdate').show();
	$('#dtNasc').parent().show(); //aparece a data de cadastro (só para visualizar)


	$.getJSON(`http://localhost/api/v1/clientes/${idCliente}`, function (data) {

		data = data[0]
		console.log(data)
		telefone = mascaraTelefone(data.telefone)

		$("#formCostumer #id").val(data.id);
		$("#formCostumer #nome").val(data.nome).prop('disabled', true);
		$("#formCostumer #dtNasc").val(data.dtNasc).prop('disabled', true);
		$("#formCostumer #telefone").val(telefone).prop('disabled', true);
		$("#formCostumer #CPF").val(data.CPF).prop('disabled', true);
		$("#formCostumer #RG").val(data.RG).prop('disabled', true);

		/* Atualizar Cliente ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Cliente atual

		
			$('#modalTitle').html('Editar Cliente');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveCostumer').val('Atualizar').show();
			$('#btnUpdate').hide();

		$("#formCostumer #nome").prop('disabled', false);
		$("#formCostumer #dtNasc").prop('disabled', false);
		$("#formCostumer #RG").prop('disabled', false);
		$("#formCostumer #CPF").prop('disabled', false);
		$("#formCostumer #telefone").prop('disabled', false);
		}); /* Fim Atualizar Usuário ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#costumerModal").modal();
		
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}


function deleteCostumer(idCliente){

	Swal.fire({
		title: 'Você tem certeza?',
		text: "Você não será capaz de reverter isso!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor:'#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `http://localhost/api/v1/clientes/${idCliente}/delete`,
				contentType: false,
				processData: false,
				/*beforeSend: function() {
					//...
				},*/
				success: function (response) {
		
					if (JSON.parse(response).error) {
						console.log('erro ao excluir!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							response.msg,
							'error'
						)
						
					} else {					
										
						Swal.fire(
							'Excluído!',
							'Registro apagado!',
							'success'
						)

						loadTableCostumers();					
					}					
				},
				error: function (response) {

					console.log(`Erro! Mensagem: ${response}`);		
				}
			});		

		}
	})

	$('.swal2-cancel').html('Cancelar');
}


//limpar campos do modal para Cadastrar
function limparCampos(){
	$('#modalTitle').html('Cadastrar Cliente');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveCostumer').val('Cadastrar').show();
	$('#btnUpdate').hide();
	


	$("#formCostumer #codigo").prop('disabled', true);
	$("#formCostumer #nome").prop('disabled', false);
	$("#formCostumer #dtNasc").prop('disabled', false);
	$("#formCostumer #RG").prop('disabled', false);
	$("#formCostumer #CPF").prop('disabled', false);
	$("#formCostumer #telefone").prop('disabled', false);


	$('#id').val(0);

	$('#nome').val('');
	$('#dtNasc').val('0');
	$('#RG').val('');
	$('#CPF').val('');
	$('#ie').val('0');
	$('#cnpj').val('');
	$('#telefone').val('');
	//...	
}

function formatDate(dateX){ //format Date to input in Form
    var data = new Date(dateX),
        dia  = data.getDate().toString(),
        diaF = (dia.length == 1) ? '0'+dia : dia,
        mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
        mesF = (mes.length == 1) ? '0'+mes : mes,
        anoF = data.getFullYear();
	return diaF+"/"+mesF+"/"+anoF;
	//return anoF+"-"+mesF+"-"+diaF;
}

$(document).on("keydown", "#complemento", function () {
    var caracteresRestantes = 150;
    var caracteresDigitados = parseInt($(this).val().length);
    var caracteresRestantes = caracteresRestantes - caracteresDigitados;

    $(".caracteres").text(caracteresRestantes);
});



$(function(){
	$("#RG").mask("00.000.000-A");
	$("#CPF").mask("999.999.999-99",{placeholder:"000.000.000-00"});
	$("#telefone").mask("(00) 0000-00009", {placeholder:"(00)0000-0000"});
	$("#cep").mask("99999-999", {placeholder:"00000-000"}); 
});


//tradução do DataTables para Português-----------------------------
const DATATABLE_PTBR = { 
    "sEmptyTable": "Nenhum registro encontrado",
    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_ resultados por página",
    "sLoadingRecords": "Carregando...",
    "sProcessing": "Processando...",
    "sZeroRecords": "Nenhum registro encontrado",
    "sSearch": "Pesquisar",
    "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
    },
    "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
    }
}//fim da tradução do DataTables -------------------------------------


function clearErrors() {
	$(".has-error").removeClass("has-error");
	$(".help-block").html("");
}

function showErrors(error_list) {
	clearErrors();

	$.each(error_list, function(id, message) {
		$(id).parent().parent().addClass("has-error");
		$(id).parent().siblings(".help-block").html(message)
	})
} 

function showErrorsModal(error_list) {
	clearErrors();

	$.each(error_list, function(id, message) {
		$(id).addClass("has-error");
		console.log(`id: ${id}, message: ${message}`)
		$(id).siblings(".help-block").html(message)
	})
} 