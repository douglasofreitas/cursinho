<h3>Total de estudantes: <?php //echo $paginator->counter(array('format' => '%count%')); ?></h3> 
<br/> 

<h3><?php echo $html->link('Filtrar', '/estudantes/filtrar/formulario'); ?></h3> 

<?php  
  //exibe links mesmo que não esteja ativado 
  //echo $paginator->prev('<< Anterior', null, null, array('class' => 'disabled')); 
  //echo $paginator->next('Próximo >>', null, null, array('class' => 'disabled')); 

  //não exibe links desativados 
  echo $paginator->prev('<< Anterior', null, null, null); 
  echo $paginator->next('Próximo >>', null, null, null); 
?> 

<table class="listagem"> 
    <tr class="listagem_header"> 
      <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td> 
        <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número', 'numero_inscricao'); ?></td> 
        <td style="width:380px"><?php echo $paginator->sort('Nome', 'nome'); ?></td>
        <td style="width:70px"><?php echo $paginator->sort('Unidade', 'Unidade.nome'); ?></td>
        <td style="width:70px"><?php echo $paginator->sort('Turma', 'Turma.nome'); ?></td>
        <td style="width:100px; text-align:center;">Advertências</td>
    </tr> 

  <?php $numero_linha = 0; ?> 

    <?php foreach ($estudantes as $estudante): ?> 

      <?php $numero_linha++ ?> 

    <?php if ($numero_linha % 2 == 1): ?> 
    <tr class="linha_impar"> 
    <?php else: ?> 
    <tr class="linha_par"> 
    <?php endif; ?> 
              <td style="text-align:center">
                    <?php echo $estudante['Candidato']['ano'] ?>
              </td> 
              <td style="text-align:center"><?php echo $estudante['Candidato']['numero_inscricao'] ?></td> 
              <td> 
                <?php 
                echo $html->link($estudante['Candidato']['nome'], 
                        '/estudantes/visualizar_ficha/' . $estudante['Candidato']['numero_inscricao'] . '/' . $estudante['Candidato']['ano'],
                        array("alt" => "Visualizar ficha", 'class' => 'tipHoverBottom', 'title' => 'Visualizar ficha'));
                ?> 
              </td> 
              <td style="text-align:center;">
                    <?php 
                    if(!empty($estudante['Candidato']['unidade_id'])) 
                        echo $unidades[$estudante['Candidato']['unidade_id']];
                    else{
                        //solicitar que seja alocado a unidade para o estudante, pelos dados do Candidato
                        echo $html->link('Alocar', '/candidatos/alterar/'.$estudante['Candidato']['numero_inscricao'].'/'.$estudante['Candidato']['ano']);
                    }
                    ?>
              </td>
              <td style="text-align:center;">
                    <?php echo $estudante['Turma']['nome'] ?>
              </td>
              <td style="text-align:center;">
                <?php 
                echo $html->link(
                    $html->image('icon/find.png', array("alt" => "Visualizar advertências", 'class' => 'tipHoverBottom', 'title' => 'Visualizar advertências')),
                    '/advertencias/visualizar_direto/' . $estudante['Estudante']['estudante_id'],
                    array('escape' => false)
                );
                echo ' ('.count($estudante['Advertencia']).')';
                ?>
              </td>
    </tr> 
    <?php endforeach; ?> 
</table> 