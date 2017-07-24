<h3 style="color: red">ATENÇÃO! O candidato não passou na fase eliminatória.</h3>
<br/> 
<h3>Deseja passá-lo na fase eliminatória antes de preencher sua prova?</h3>
<br/>
<h3>Lembrando que se o candidato não passar na fase eliminatória, ele não será considerado na fase classificatória pelo sistema.</h3>
<br/>
<h3><?php echo $html->link('Passar o candidato na fase eliminatória', '/resposta_questao_provas/confirmar_preenchimento/1/'.$numero_inscricao.'/'.$ano, array('style' => 'color:#0000CC')); ?></h3>
<br/>
<h3><?php echo $html->link('Não passar o candidato na fase eliminatória', '/resposta_questao_provas/confirmar_preenchimento/0/'.$numero_inscricao.'/'.$ano, array('style' => 'color:#0000CC')); ?></h3>
<br/>