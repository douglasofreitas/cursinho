
<?php
function codificar($string){
    if((isset($string)) && (is_string($string))){
        $enc_string = base64_encode($string);
        $enc_string = str_replace("=","",$enc_string);
        $enc_string = strrev($enc_string);
        $md5 = md5($string);
        $enc_string = substr($md5,0,3).$enc_string.substr($md5,-3);
    }else{
        $enc_string = "Parâmetro incorreto ou inexistente!";
    }
    return $enc_string;
}
function descodificar($string){
    if((isset($string)) && (is_string($string))){
        $ini = substr($string,0,3);
        $end = substr($string,-3);
        $des_string = substr($string,0,-3);
        $des_string = substr($des_string,3);
        $des_string = strrev($des_string);
        $des_string = base64_decode($des_string);
        $md5 = md5($des_string);
        $ver = substr($md5,0,3).substr($md5,-3);
        if($ver != $ini.$end){
            $des_string = "Erro na desencriptação!";
        }
    }else{
        $des_string = "Parâmetro incorreto ou inexistente!";
    }
    return $des_string;
}
?>


<script type="text/javascript">
    $(function() {

        $('a#link_filtro').click(function() {
                $('#form_filtro').show('fast');
        });
    });
</script>

<h3>Total de faturas: <?php echo $paginator->counter(array('format' => '%count%')); ?></h3>
<br/>

<h3><?php echo $html->link('Filtrar', '#', array('id' => 'link_filtro')); ?></h3>
<?php
if($tipo == 'candidato'):
?>
    <div id='form_filtro' style="display:none">
        <?php
        echo $form->create('Fatura', array('action' => 'index/candidato', 'class' => 'formulario', 'type' => 'GET'));

        echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
        echo '<br/>';

        echo $form->input('numero_inscricao', array('label' => 'Número de inscrição', 'size' => '10'));
        echo '<br/>';

        echo $form->input('nome', array('label' => 'Nome', 'size' => '60'));
        echo '<br/>';

        echo $form->input('nossonumero', array('label' => 'Nosso Número', 'size' => '30'));
        echo '<br/>';
        echo $form->input('valor', array('label' => 'Valor', 'size' => '30'));
        echo '<br/>';

        echo $form->button('Filtrar', array('type' => 'submit'));
        echo $form->button('Limpar o formulário', array('type' => 'reset'));

        echo $form->end();
        ?>
    </div>
<?php
else:
?>
    <div id='form_filtro' style="display:none">
        <?php
        echo $form->create('Fatura', array('action' => 'index/estudante', 'class' => 'formulario'));

        echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
        echo '<br/>';

        echo $form->input('numero_inscricao', array('label' => 'Número de inscrição', 'size' => '10'));
        echo '<br/>';

        echo $form->input('nome', array('label' => 'Nome', 'size' => '60'));
        echo '<br/>';

        echo $form->input('nossonumero', array('label' => 'Nosso Número', 'size' => '30'));
        echo '<br/>';
        echo $form->input('valor', array('label' => 'Valor', 'size' => '30'));
        echo '<br/>';

        echo $form->button('Filtrar', array('type' => 'submit'));
        echo $form->button('Limpar o formulário', array('type' => 'reset'));

        echo $form->end();
        ?>
    </div>
<?php
endif;
?>

<br/>
<?php  
  //exibe links mesmo que não esteja ativado 
  //echo $paginator->prev('<< Anterior', null, null, array('class' => 'disabled')); 
  //echo $paginator->next('Próximo >>', null, null, array('class' => 'disabled')); 

  //não exibe links desativados
  $paginator->options(array('url' => array('?' => $param_get)));


  echo $paginator->prev('<< Anterior', null, null, null).' &nbsp; '; 
  echo $paginator->numbers().' &nbsp; ';
  echo $paginator->next('Próximo >>', null, null, null); 
?> 

<?php
if($faturas):
?>

    <table class="listagem">
        <tr class="listagem_header">
          <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'Candidato.ano'); ?></td>
            <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número de inscrição', 'Candidato.numero_inscricao'); ?></td>
            <td style="width:auto; text-align:center;"><?php echo $paginator->sort('Nome', 'Candidato.nome'); ?></td>
            <td style="width:80px"><?php echo $paginator->sort('Valor', 'valor'); ?></td>
            <td style="width:80px"><?php echo $paginator->sort('Nosso Número', 'nosonumero'); ?></td>
            <td style="width:80px"><?php echo $paginator->sort('Status', 'pago'); ?></td>
            <td style="width:80px"><?php echo $paginator->sort('Vencimento', 'data_vencimento'); ?></td>
            <td style="width:72px"><?php echo $paginator->sort('Criação', 'created'); ?></td>
            <td style="width:100px; text-align:center;">Ações</td>
        </tr>

      <?php $numero_linha = 0; ?>

        <?php foreach ($faturas as $fatura): ?>

          <?php $numero_linha++ ?>

        <?php if ($numero_linha % 2 == 1): ?>
        <tr class="linha_impar">
        <?php else: ?>
        <tr class="linha_par">
        <?php endif; ?>
                  <td style="text-align:center">
                    <?php
                    if($tipo == 'candidato')
                        echo $fatura['Candidato']['ano'];
                    else
                        echo $candidatos[$fatura['Estudante']['estudante_id']]['ano'] ;
                    ?>
                  </td>
                  <td style="text-align:center">
                      <?php
                      if($tipo == 'candidato')
                          echo $fatura['Candidato']['numero_inscricao'];
                      else
                          echo $candidatos[$fatura['Estudante']['estudante_id']]['numero_inscricao'] ;
                      ?>
                  </td>
                  <td style="text-align:center">
                      <?php
                      if($tipo == 'candidato')
                          if(!empty($fatura['Candidato']['candidato_id']))
                              echo $html->link(
                                  $fatura['Candidato']['nome'],
                                  '/candidatos/visualizar/' . $fatura['Candidato']['numero_inscricao'].'/'.$fatura['Candidato']['ano'],
                                  array('escape' => false, 'target' => '_blank')
                              );
                          else
                              echo 'Sem candidato associado';

                      else
                          if(!empty($candidatos[$fatura['Estudante']['estudante_id']]['candidato_id']))
                              echo $html->link(
                                  $candidatos[$fatura['Estudante']['estudante_id']]['nome'],
                                  '/candidatos/visualizar/' . $candidatos[$fatura['Estudante']['estudante_id']]['numero_inscricao'].'/'.$candidatos[$fatura['Estudante']['estudante_id']]['ano'],
                                  array('escape' => false, 'target' => '_blank')
                              );
                          else
                              echo 'Sem candidato associado';
                      ?>
                  </td>
                  <td>
                    <?php echo number_format($fatura['Fatura']['valor'], 2, ',', ''); ?>
                  </td>
                  <td style="text-align:center"><?php echo $fatura['Fatura']['nossonumero'] ?></td>

                  <td style="text-align:center;">
                        <?php
                        if($fatura['Fatura']['pago'] == 0)
                            echo '<span style="color:red">Não pago</span>';
                        else{
                            echo '<span style="color:blue">Pago</span>';
                        }
                        ?>
                  </td>
            <td>
                <?php echo date('d/m/Y', strtotime($fatura['Fatura']['data_vencimento'])); ?>
            </td>
                <td>
                    <?php echo date('d/m/Y', strtotime($fatura['Fatura']['created'])); ?>
                </td>
                <td style="text-align:center;">
                    <?php
                    if($tipo == 'candidato'){
                        if(!empty($fatura['Candidato']['candidato_id'])){
                            if($fatura['Fatura']['pago'] == 0){

                                echo $html->link(
                                    $html->image('icon/page_white_edit.png', array("alt" => "Editar", 'class' => 'tipHoverBottom', 'title' => 'Editar ')),
                                    '/faturas/editar/' .$fatura['Fatura']['id'],
                                    array('escape' => false)
                                );
                                echo ' ';
                                echo $html->link(
                                    $html->image('icon/coins.png', array("alt" => "Baixa manual", 'class' => 'tipHoverBottom', 'title' => 'Baixa manual')),
                                    '/faturas/baixa_manual/' . $fatura['Fatura']['id'],
                                    array('escape' => false),
                                    'Deseja realmente dar baixa manual desta fatura?'
                                );
                            }
                            ?>
                            <a style="" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($fatura['Fatura']['id']) ; ?>">
                                <?php echo $html->image('icon/printer.png', array("alt" => "Imprimir boleto", 'class' => 'tipHoverBottom', 'title' => 'Imprimir boleto')) ?>

                            </a>
                            <?php

                        }else{
                            echo $html->link(
                                $html->image('icon/cross.png', array("alt" => "Remover", 'class' => 'tipHoverBottom', 'title' => 'Remover')),
                                '/faturas/delete/' . $fatura['Fatura']['id'],
                                array('escape' => false),
                                'Deseja realmente remover esta fatura?'
                            );
                        }

                    }else{
                        if(!empty($candidatos[$fatura['Estudante']['estudante_id']]['candidato_id'])){
                            if($fatura['Fatura']['pago'] == 0){

                                echo $html->link(
                                    $html->image('icon/page_white_edit.png', array("alt" => "Editar", 'class' => 'tipHoverBottom', 'title' => 'Editar ')),
                                    '/faturas/editar/' .$fatura['Fatura']['id'],
                                    array('escape' => false)
                                );
                                echo ' ';
                                echo $html->link(
                                    $html->image('icon/coins.png', array("alt" => "Baixa manual", 'class' => 'tipHoverBottom', 'title' => 'Baixa manual')),
                                    '/faturas/baixa_manual/' . $fatura['Fatura']['id'],
                                    array('escape' => false),
                                    'Deseja realmente dar baixa manual desta fatura?'
                                );
                            }
                            ?>
                            <a style="" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($fatura['Fatura']['id']) ; ?>">
                                <?php echo $html->image('icon/printer.png', array("alt" => "Imprimir boleto", 'class' => 'tipHoverBottom', 'title' => 'Imprimir boleto')) ?>

                            </a>
                        <?php

                        }else{
                            echo $html->link(
                                $html->image('icon/cross.png', array("alt" => "Remover", 'class' => 'tipHoverBottom', 'title' => 'Remover')),
                                '/faturas/delete/' . $fatura['Fatura']['id'],
                                array('escape' => false),
                                'Deseja realmente remover esta fatura?'
                            );
                        }
                    }
                    ?>


                </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php
endif;
?>


 <?php
    echo $paginator->prev('<< Anterior', null, null, null).' &nbsp; '; 
    echo $paginator->numbers().' &nbsp; ';
    echo $paginator->next('Próximo >>', null, null, null); 
 ?>