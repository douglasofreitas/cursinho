<h3>Alterar Sala</h3>
<br />
Estudante: <?php echo $estudante['Candidato']['nome'];?><br />
<br />
Ano Letivo: <?php echo $ano;?><br />
<br />
Escolha na tabala abaixo a turma que deseja alocar o aluno:<br />
<br />
<?php
if(count($turmas_disponiveis)> 0):
?>
    <table class="listagem">
      <tr class="listagem_header">
        <td>Turma</td>
        <td>Unidade</td>
        <td></td>
      </tr>
    <?php
            foreach ($turmas_disponiveis as $turma)
            {
                    echo '<tr><td> '.$turma['Turma']['nome'].' </td><td> '.$turma['Unidade']['nome'].' </td> <td>';
                    echo $html->link('Inserir na turma', '/estudantes/alterar_turma_direto/'.$estudante['Estudante']['estudante_id'].'/'.$turma['Turma']['id']);
                    echo '</td></tr>';
            }

    ?>
    </table>
<?php
else:
?>
    <strong>Não há turmas cadastradas. Volte e tente outra opção</strong>
<?php
endif;
?>
