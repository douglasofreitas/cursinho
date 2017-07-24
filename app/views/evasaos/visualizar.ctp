<ul>
<li><h3><?php echo $html->link('Editar evasão', '/evasaos/editar_direto/' . 
		$this->data['Evasao']['estudante_id']) ?></h3></li>

<li><h3><?php echo $html->link('Remover evasão', '/evasaos/remover/' . 
		$this->data['Evasao']['estudante_id']) ?></h3></li>
</ul>
<br/>
<label class="formulario">Nome do estudante:</label>
<?php echo $this->data['Candidato']['nome'] ?>
<br/>
<label class="formulario">Data da Evasão:</label>
<?php echo $this->data['Evasao']['data'] ?>
<br/>
<label class="formulario">Motivo:</label>
<?php echo $this->data['Evasao']['motivo'] ?>
<br/>