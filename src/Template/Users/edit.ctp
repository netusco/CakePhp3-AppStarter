<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user, ['type' => 'post']); ?>
    <fieldset>
        <legend><?= __('Editer le profil') ?></legend>
        <?php
        echo $this->Form->input('nom', array(
            'label' => 'Nom'
        ));
        echo $this->Form->input('prenom', array(
            'label' => 'Prénom'
        ));
        echo $this->element('PhotoCrop.photocrop_input', [
            'data' => [
                'photocropType' => 'profile',
                'displaySavedPhotocrops' => true,
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
