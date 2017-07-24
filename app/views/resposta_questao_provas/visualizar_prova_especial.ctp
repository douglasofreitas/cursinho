<h3>Número de inscrição: <?php echo $numero_inscricao;?></h3> 
<br/>
<h3>Ano: <?php echo $ano;?></h3>
<br/>
<br/>
<h3><?php echo $html->link('Editar a nota da prova', '/resposta_questao_provas/alterar/'.$numero_inscricao.'/'.$ano, array('style' => 'color:#0000CC'));?></h3>
<br/>
<br/>
<h2>Nota da prova especial: <?php echo $nota_prova;?></h2> 
<br/>