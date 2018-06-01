<?php 
    $tutor = $this->tutor; 
?>
<h5 class="text-center text-secondary">Datos del Tutor</h5>
<hr class="mt-1">
<?php if ($this->tutor): ?>
<form id="updateTutorForm" method="POST" class="form-horizontal">
    <div class="form-group row">
        <label class="col-12 text-center">Nombre Completo:</label>
        <div class="col-12 input-group">
            <input type="hidden" id="tutor_id" name="tutor" value="<?= $tutor->id_tutor; ?>">
            <input type="text" 
                   class="form-control form-control-sm text-center" 
                   id="surname" 
                   name="surname"
                   pattern="[a-zA-Z\s]{3,60}" 
                   value="<?= $tutor->surnamet; ?>"
                   autofocus
                   autocomplete="off">

            <input type="text" 
                   class="form-control form-control-sm text-center" 
                   id="lastname" 
                   name="lastname"
                   pattern="[a-zA-Z\s]{3,60}" 
                   value="<?= $tutor->lastnamet; ?>" 
                   autocomplete="off">

            <input type="text" 
                   class="form-control form-control-sm text-center" 
                   id="name" 
                   name="name"
                   pattern="[a-zA-Z\s]{3,60}" 
                   value="<?= $tutor->namet; ?>" 
                   autocomplete="off">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Ocupación:</label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm" 
                           id="ocupacion" 
                           name="ocupation" 
                           value="<?= $tutor->job; ?>"
                           autocomplete="off">
                </div>
            </div>
        </div>      
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Parentesco: </label>
                <div class="col-12">
                    <select class="form-control form-control-sm " name="relationship">
                        <option value="" hidden>Seleccione...</option>
                        <option value="Madre" 
                                <?= $tutor->relationship == 'Madre' ? 'selected' : ''; ?>>Madre</option>
                        <option value="Padre" 
                                <?= $tutor->relationship == 'Padre' ? 'selected' : ''; ?>>Padre</option>
                        <option value="Abuelo(a)" 
                                <?= $tutor->relationship == 'Abuelo(a)' ? 'selected' : ''; ?>>Abuelo(a)</option>
                        <option value="Hermano(a)" 
                                <?= $tutor->relationship == 'Hermano(a)' ? 'selected' : ''; ?>>Hermano(a)</option>
                        <option value="Tio(a)" 
                                <?= $tutor->relationship == 'Tío(a)' ? 'selected' : ''; ?>>Tío(a)</option>
                        <option value="Tutor" 
                                <?= $tutor->relationship == 'Tutor' ? 'selected' : ''; ?>>Tutor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Teléfono de Casa: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm" 
                           id="phone" 
                           name="phone" 
                           value="<?= $tutor->phone; ?>" 
                           autocomplete="off">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Teléfono Celular: </label>
                <div class="col-12">
                      <input type="tel" 
                           class="form-control form-control-sm" 
                           id="cellphone" 
                           name="cellphone" 
                           value="<?= $tutor->cellphone; ?>"
                           autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Otro Familiar: </label>
                <div class="col-12">
                    <select class="form-control form-control-sm " name="relationship_alt">
                        <option value="" hidden>Seleccione...</option>
                        <option value="Madre" 
                                <?= $tutor->relationship_alt == 'Madre' ? 'selected' : ''; ?>>Madre</option>
                        <option value="Padre" 
                                <?= $tutor->relationship_alt == 'Padre' ? 'selected' : ''; ?>>Padre</option>
                        <option value="Abuelo(a)" 
                                <?= $tutor->relationship_alt == 'Abuelo(a)' ? 'selected' : ''; ?>>Abuelo(a)</option>
                        <option value="Hermano(a)" 
                                <?= $tutor->relationship_alt == 'Hermano(a)' ? 'selected' : ''; ?>>Hermano(a)</option>
                        <option value="Tio(a)" 
                                <?= $tutor->relationship_alt == 'Tio(a)' ? 'selected' : ''; ?>>Tío(a)</option>
                        <option value="Tutor" 
                                <?= $tutor->relationship_alt == 'Tutor' ? 'selected' : ''; ?>>Tutor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Teléfono de Familiar: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm" 
                           id="phone_alt" 
                           name="phone_alt" 
                           value="<?= $tutor->phone_alt; ?>" 
                           pattern="[0-9\s]{8,15}"
                           autocomplete="off">
                </div>
            </div>
        </div>  
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-6 text-center">
            <button type="button" id="update_tutor" class="btn btn-primary">Actualizar</button>
        </div>
    </div>
</form>
<?php else: ?>

<h4 class="text-center text-info my-4">Alumno sin tutor</h4>

<?php endif ?>

