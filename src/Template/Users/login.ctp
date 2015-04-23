<h1>Login</h1>
<?= $this->Form->create() ?>
<fieldset>
    <legend><?php echo __('Login'); ?></legend>
    <?php
        echo $this->Form->input('email', array(
            "label" => "Email",
            "type" => "email",
            "autocomplete" => "on"
        ));
        echo $this->Form->input('password', array(
            "label" => "Mot de passe",
            "type" => "password",
            "autocomplete" => "off"
        ));
    ?>
</fieldset>
<?= $this->Form->button('Valider', array("type" => "submit")); ?>
<?= $this->Html->link("Réinitialiser mot de passe", 
        array("action" => "reinitialiserMotPasse"), 
        array("id" => "reset-password", "class" => "button"));?>
<?= $this->Form->end() ?>