<h2>Admin interface</h2>
<?php $user = lcUser::getById($_SESSION['user_id']);?>

<p>Bonjour <?php echo $user->login;?>, <a href="<?php $this->url('/user/logout');?>" >se déconnecter</a></p>
<p>J'affiche la variable test : <?php echo $test;?></p>