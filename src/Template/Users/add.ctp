<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user); ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
        echo $this->Form->input('nom', array(
            'label' => 'Nom'
        ));
        echo $this->Form->input('prenom', array(
            'label' => 'Prénom'
        ));
        echo $this->Form->input('email', array(
            'label' => 'Adresse e-mail'
        ));
        echo $this->Form->input('password', array(
            'label' => 'Choisir un mot de passe',
            'value' => false,
        ));
        echo $this->element('PhotoCrop.photocrop_input', [
            'data' => [
                'photocropType' => 'profile',
                'displaySavedPhotocrops' => false,
                'entity' => $user, 
                'inputPhotocropLabel' => 'Ajouter une photo',
                'inputPhotocropLabelClass' => 'photocrop_label',
                'inputPhotocropClass' => 'form__input--photocrop',
            ]
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Valider')) ?>
    <?= $this->Form->end() ?>
</div>
