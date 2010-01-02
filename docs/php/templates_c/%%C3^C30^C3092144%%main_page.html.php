<?php /* Smarty version 2.6.26, created on 2010-01-02 22:11:26
         compiled from ../modules/main_page/templates/main_page.html */ ?>
<div class="bubble_items1 bubble">
    <?php $_from = $this->_tpl_vars['clouds']['first']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tag']):
?>
        <a style="font-size:<?php echo $this->_tpl_vars['tag']['ratio']; ?>
em; font-weight:<?php echo $this->_tpl_vars['tag']['fontWeight']; ?>
"
        href="http://sozluk.sourtimes.org/show.asp?t=<?php echo $this->_tpl_vars['tag']['title']; ?>
"><?php echo $this->_tpl_vars['tag']['title']; ?>
</a>&nbsp; &nbsp;
    <?php endforeach; endif; unset($_from); ?>
    
    <div class="bubble_items2 bubble">
        <?php $_from = $this->_tpl_vars['clouds']['second']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tag']):
?>
            <a style="font-size:<?php echo $this->_tpl_vars['tag']['ratio']; ?>
em; font-weight:<?php echo $this->_tpl_vars['tag']['fontWeight']; ?>
" href="http://sozluk.sourtimes.org/show.asp?t=<?php echo $this->_tpl_vars['tag']['title']; ?>
" ><?php echo $this->_tpl_vars['tag']['title']; ?>
</a>&nbsp; &nbsp;

        <?php endforeach; endif; unset($_from); ?>
    </div>

</div>





