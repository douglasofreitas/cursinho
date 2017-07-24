<script>
    function alterar_turma(){
        var titulo = 'Escolha o ano letivo';
        var mensagem = '<?php  
        echo $html->link(date('Y'), '/estudantes/alterar_turma/'.$estudante['Estudante']['estudante_id'].'/'.date('Y'), array('style' => 'color:#0000CC'));
        echo ' - '.$html->link(date('Y')-1, '/estudantes/alterar_turma/'.$estudante['Estudante']['estudante_id'].'/'.(date('Y')-1), array('style' => 'color:#0000CC'));
        echo ' - '.$html->link(date('Y')-2, '/estudantes/alterar_turma/'.$estudante['Estudante']['estudante_id'].'/'.(date('Y')-2), array('style' => 'color:#0000CC'));
        echo ' - '.$html->link(date('Y')-3, '/estudantes/alterar_turma/'.$estudante['Estudante']['estudante_id'].'/'.(date('Y')-3), array('style' => 'color:#0000CC'));
        ?>';
        popupMsg(titulo,mensagem);
        return false;
    }
    function rematricular(){
        var titulo = 'Rematricular';
        var mensagem = '<?php echo $form->create('Estudante', array('action' => 'rematricular', 'class' => 'formulario')); ?>'+
    '<input type="hidden" name="data[Estudante][candidato_id]" value="<?php echo $estudante['Estudante']['candidato_id'] ?>"/>  '+
    '<input type="hidden" name="data[Estudante][estudante_id]" value="<?php echo $estudante['Estudante']['estudante_id'] ?>"/>  '+
    '<br/><strong>Nota de prova</strong> '+
    '<input type="text" name="data[Estudante][prova_nota_rematriculado]" value=""/> '+
    '<?php echo $form->end('Salvar'); ?> ';
        popupMsg(titulo,mensagem);
        return false;
    }
</script>

<h3>Estudante: <?php echo $estudante['Candidato']['nome'] ?></h3><br/>
<h3>Número de inscrição: <?php echo $estudante['Candidato']['numero_inscricao'].'/'.$estudante['Candidato']['ano'] ?></h3><br/>
<h3>Código do estudante: <?php echo $estudante['Estudante']['estudante_id'] ?></h3><br/>
<h3>Ano Letivo: <?php echo $estudante['Estudante']['ano_letivo'] ?></h3><br/>
<h3><?php echo $html->link('Turma: '.$codigo_turma, '#', array('onclick'=>'alterar_turma()', 'style' => 'color:#0000CC')) ?> 
    <br/><br/>
<?php if($dias_totais == 0):?>
    <h3 style="color:red"> 
        Sem Frequência. (ano corrente: <?php echo $estudante['Estudante']['ano_letivo'] ?>)<br/>
    </h3><br/>
<?php else:?>
    <h3 style="color:<?php if ($frequencia_count >= 75) echo 'blue'; else echo 'red'; ?>">
        Frequência: <?php echo $frequencia_count.'';?>% (ano corrente: <?php echo $estudante['Estudante']['ano_letivo'] ?>)
    </h3><br/>
<?php endif;?>
<!-- Santana  -->
<?php if ($pendentes == -1): ?>
<h3><?php echo $html->link('Nenhuma mensalidade gerada. Clique aqui para gerar', '/estudantes/gerar_mensalidades/'.$estudante['Estudante']['estudante_id'], array('style' => 'color:red'))?></h3>
<?php elseif ($pendentes > 0): ?>
<h3><?php echo $html->link('Há ' . $pendentes . ' mensalidade(s) atrasada(s)', '/estudantes/visualizar_mensalidades/'.$estudante['Estudante']['estudante_id'], array('style' => 'color:red'))?></h3>
<?php else:?>
<h3><?php echo $html->link('Mensalidade em dia', '/estudantes/visualizar_mensalidades/'.$estudante['Estudante']['estudante_id'], array('style' => 'color:green'))?></h3>
<?php endif;?>
<br/>
<?php
//verificar se pode exibir o link de rematrícula.
if($estudante['Estudante']['ano_letivo'] == $estudante['Candidato']['ano']){
    if(empty($estudante['Candidato']['rematriculado'])){
        echo '<h3>'.$html->link('Rematricular estudante', '#', array('style' => 'color:black', 'onclick'=>'rematricular()')).'</h3>';
        echo '<br/>';
    }
}
?>

<h3><b>Opções gerais</b></h3>
<hr/>
<h3><?php echo $html->link('Listar advertências', '/advertencias/visualizar_direto/'.$estudante['Estudante']['estudante_id']) ?></h3><br/>
<h3><?php echo $html->link('Visualizar mensalidades', '/estudantes/visualizar_mensalidades/'.$estudante['Estudante']['estudante_id']) ?></h3><br/>
<h3><?php echo $html->link('Visualizar boletim', '/boletims/visualizar_direto/'.$estudante['Estudante']['estudante_id']) ?></h3><br/>
<h3><?php echo $html->link('Visualizar ficha de inscrição', '/candidatos/visualizar/' . $estudante['Candidato']['numero_inscricao'] . '/' . $estudante['Candidato']['ano']) ?></h3><br/>
<br/>

<?php if(false): ?>
    <h3><b>Emissão de formulários</b></h3>
    <hr/>
    <h3><?php echo $html->link('* Formulário de pedido de revisão do valor da mensalidade', '#', array('class' => 'tipHoverBottom', 'title' => 'Em desenvolvimento')) ?></h3><br/>
    <h3><?php echo $html->link('* Formulário de pedido de mudança de turma', '#', array('class' => 'tipHoverBottom', 'title' => 'Em desenvolvimento')) ?></h3><br/>
    <br/>
<?php endif; ?>
<h3><b>Emissão de atestados, declarações e cartas</b></h3>
<hr/>
<h3><?php echo $html->link('Atestado de matrícula', '/estudantes/relatorio_atestado_matricula/'.$estudante['Estudante']['estudante_id']) ?></h3><br/>
<?php 
if(false): 
    //por enquanto não foi pedido
?>
    <h3><?php echo $html->link('* Declaração de frequência', '#', array('class' => 'tipHoverBottom', 'title' => 'Em desenvolvimento')) ?></h3><br/>
<?php 
endif; 
?>
<?php if(count($estudante['Fatura']) > 0): ?>
    <h3><?php echo $html->link('Carta de mensalidade', '/estudantes/carta_de_mensalidade/'.$estudante['Estudante']['estudante_id']) ?></h3><br/>
<?php else: ?>
    <h3><?php echo $html->link('* Carta de mensalidade', '#', array('class' => 'tipHoverBottom', 'title' => 'Não há mensalidades criadas')) ?></h3><br/>
<?php endif; ?>

<?php 
if(false): 
    //por enquanto não foi pedido
?>
    <h3><?php echo $html->link('* Carta do estudante', '#', array('class' => 'tipHoverBottom', 'title' => 'Em desenvolvimento')) ?></h3><br/>
<?php 
endif; 
?>