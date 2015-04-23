<div id="login" class="change_pass">
    <fieldset>
        <legend><?= 'Changement du mot de passe'; ?></legend>
    <?php
    echo $this->Form->create("User");
        if(is_null($change_pass_code)):
            echo $this->Form->input("password", array(
                "label" => "Votre ancien mot de passe",
                "type" => "password",
                "required" => true,
                "autocomplete" => "off"
            ));
        else:
            echo $this->Form->input("from_nouveauMotPasse", array(
                "type" => "hidden",
                "value" => $change_pass_code
            ));
        endif;
        
        echo $this->Form->input("new_pass", array(
            "label" => "Votre nouveau mot de passe",
            "placeholder" => "Une majuscule, un chiffre et 8 caractères minimum",
            "type" => "password",
            "required" => true,
            "div" => array('class' => 'required'),
            "autocomplete" => "off"
        ));
        echo $this->Form->input("new_pass_confirm", array(
            "label" => "Confirmez le nouveau mot de passe",
            "type" => "password",
            "required" => true,
            "div" => array('class' => 'required'),
            "autocomplete" => "off"
        ));
        echo $this->Form->button("Valider le nouveau mot de passe", array("type" => "submit"));
    echo $this->Form->end();
    ?>
    </fieldset>
</div>