<?php /* Smarty version 2.6.26, created on 2010-01-02 22:11:26
         compiled from mainContent.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'mainContent.html', 77, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Ek$igator : Ekşisözlük Başlık Takip Aparatı</title>

        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['URL']; ?>
templates/css/reset.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['URL']; ?>
templates/css/style.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['URL']; ?>
modules/<?php echo $this->_tpl_vars['moduleName']; ?>
/templates/css/style.css" type="text/css" />

        <script type="text/javascript" src="http://cdn.jquerytools.org/1.1.2/full/jquery.tools.min.js"></script>
        <script type="text/javascript" src="http://jscroller.googlecode.com/files/jscroller-0.4.js"></script>

        <script type="text/javascript" src="<?php echo $this->_tpl_vars['URL']; ?>
templates/js/general.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['URL']; ?>
modules/<?php echo $this->_tpl_vars['moduleName']; ?>
/templates/js/script.js"></script>
        
        <meta name="keywords" content="" />
        <meta name="description" content=""/>
    </head>
    <body>

    <div class="center">    


        <div id="top">

            <div class="left_space float_left">
                <div class="menu">
                    <div class="menu_items">
                        <div class="menu_logo">
                               <a href="<?php echo $this->_tpl_vars['URL']; ?>
">ek$igator</a>
                        </div>
                        <?php if ($this->_tpl_vars['userLoggedIn']): ?>
                            <div style="text-align:center">

                                <?php if ($this->_tpl_vars['userLoggedIn']['eksigator']['auth'] > 0): ?>
                                    <a href="<?php echo $this->_tpl_vars['URL']; ?>
admin">Yönetim Paneli</a> <br/>
                                    <br/>
                                <?php endif; ?>
                                <div style="text-decoration:underline">API Anahtarınız</div>

                                <span class="apiKey"><?php echo $this->_tpl_vars['userLoggedIn']['eksigator']['apiKey']; ?>
</span> <br/>
                                <div class="kayitParola">
                                    <a href="<?php echo $this->_tpl_vars['URL']; ?>
uye/cikis">Çıkış</a> 
                                </div>
                            </div>
                        <?php else: ?>
                            <form method="post" action="<?php echo $this->_tpl_vars['URL']; ?>
uye/giris">
                                <div>
                                        E-posta adresiniz <br/>
                                    <input class="inputText username" type="text" name="email" /> <br/>
                                        Parolanız <br/>
                                    <input class="inputText password" type="password" name="password"/> <br/>
                                    <input class="inputButton" type="submit" value="Giriş"/>
                                </div>
                            </form>
                            <div class="kayitParola">
                                <a href="<?php echo $this->_tpl_vars['URL']; ?>
uye/kayit">Kayıt</a>  <br/>
                                <a href="<?php echo $this->_tpl_vars['URL']; ?>
uye/parolamiUnuttum">Parolamı Unuttum</a> 
                            </div>
                        <?php endif; ?>


                    </div>
                </div> <!-- menu -->
               
                <div class="ads">
                    Buraya reklamlar gelir 
                </div>

            </div>

            <div id="main" class="<?php echo $this->_tpl_vars['moduleName']; ?>
 float_left">
                <?php if ($this->_tpl_vars['noSuchModule']): ?>
                    <div class="noSuchModule">
                            <div>
                            <?php echo ((is_array($_tmp='view/viewNotExists')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
                
                            </div>
                    </div>
                <?php endif; ?>
                    <?php echo $this->_tpl_vars['moduleContent']; ?>

            </div> <!-- main -->


        </div> <!-- top -->

        <div id="bottom">
            
            <div class="bottom_left float_left">
                    
                <div class="footer_top">    
                </div>

                <div class="footer_bottom">
                    <ul>
                        <li>
                        <img src="<?php echo $this->_tpl_vars['URL']; ?>
templates/images/copyleft.jpg" alt="copyleft" title="copyleft"/> 2009 Ek$igator
                        </li>
                        <li>                   
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
sayfa/hakkinda">Hakkında</a>
                        </li>
                        <li>
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
sayfa/eklentiler">Eklentiler</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
sayfa/kaynakKodu">Kaynak kodu</a>
                        </li>
                        <li>
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
sayfa/API">API</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
sayfa/hizmetler">Hizmetler</a>
                        </li>
 
                        <li>
                            <a href="<?php echo $this->_tpl_vars['URL']; ?>
iletisim">İletişim</a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="logo float_right"> </div>

            <div class="clear"></div>


        </div> <!-- bottom -->


        <!--<script type="text/javascript" src="js/analytics.js"></script>-->
    </div>


    </body>
</html>