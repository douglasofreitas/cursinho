<script type="text/javascript">
	$(function() {
        $("#formulario").validate({
            rules: {
                'data[User][password]': {
                    required: true
                },
                'data[User][password2]': {
                    required: true,
                    equalTo: "#UserPassword"
                }
            },
            messages: {
                'data[User][password]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[User][password2]': {
                    required: "<span style='color:red'>Obrigatório!</span>",
                    equalTo: "<span style='color:red'>Senhas não coincidem</span>"
                }
            }
        });

	});
</script>

<h3>Informe a nova senha<br/><br/>
Se houver dificuldade em realizar a inscrição entre em contato com o cursinho<br/>
    <br/>
Candidato: <?php echo $candidato['nome'] ?>
</h3>
<br />
<br/>
<?php
	echo $form->create('User', array('action' => 'muda_senha', 'class' => 'formulario', 'id' => 'formulario'));

echo $form->input('password', array('label' => 'Nova Senha', 'size' => '15', 'type' => 'password'));
echo '<br/>';
echo $form->input('password2', array('label' => 'Confirmar Nova Senha', 'size' => '15', 'type' => 'password'));
echo '<br/>';


	echo $form->end('Salvar');

	//$options = array('url' => array('action' => 'atualiza_cidades'), 'update' => 'cidades', 'frequency' => 0.2);

	//echo $ajax->observeField('CandidatoEstado', $options);

?>
