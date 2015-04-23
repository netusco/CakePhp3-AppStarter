<div class="login-alert">
    <p>En validant ce formulaire, <strong>un e-mail contenant un lien d'activation</strong> vous sera envoyé et permettra de régénérer un nouveau mot de passe pour votre compte.</p>
</div>

<div id="login" class="change_pass">
    <fieldset>
        <legend><?= 'Reinitialiser le mot de passe'; ?></legend>
    <?php
    echo $this->Form->create("User");
        echo $this->Form->input('email', array(
                "label" => "Email",
                "type" => "email",
                "autocomplete" => "on"
        ));

        echo $this->Form->button("Valider", array("type" => "submit"));
        echo $this->Html->link("Annuler la demande", "/", array("class" => "button"));
    echo $this->Form->end();
    ?>
    </fieldset>
</div>