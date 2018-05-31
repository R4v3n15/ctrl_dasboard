<?php 
    $action = Config::get('URL') . "alumno/actualizarDatosAlumno";
    $alumno = $this->alumno; 
?>
<h5 class="text-center text-secondary">Datos Del Alumno</h5>
<hr class="mt-1"></hr>
<form action="<?= $action; ?>" class="form-horizontal" method="POST">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="fullname" class="col-sm-12 text-center">Nombre Completo:</label>
                <div class="input-group">
                    <input type="hidden" name="student" value="<?= $alumno->student_id; ?>">
                    <input type="text" 
                          class="form-control form-control-sm text-center" 
                          id="surname" 
                          name="surname"
                          autofocus
                          value="<?= $alumno->surname; ?>"
                          pattern="[a-zA-Z\s]{3,60}"
                          autocomplete="off" required>

                   <input type="text" 
                          class="form-control form-control-sm text-center" 
                          id="lastname" 
                          name="lastname" 
                          value="<?= $alumno->lastname; ?>"
                          pattern="[a-zA-Z\s]{3,60}" 
                          autocomplete="off" required>

                   <input type="text" 
                          class="form-control form-control-sm text-center" 
                          id="name" 
                          name="name" 
                          value="<?= $alumno->name; ?>"
                          pattern="[a-zA-Z\s]{3,60}"
                          autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Fecha de Nacimiento: </label>
                <div class="col-12">
                    <input type="text" 
                           id="bdate" 
                           class="form-control form-control-sm"
                           placeholder="Fecha de Nacimiento"
                           value="<?= $alumno->birthday; ?>"
                           name="bdate">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Sexo: </label>
                <div class="col-12">
                    <select class="form-control form-control-sm" name="genre">
                        <option value="Masculino" <?= $alumno->genre == 'Masculino' ? 'checked' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?= $alumno->genre == 'Femenino' ? 'checked' : ''; ?>>Femenino</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row">
                <label class="col-12">Estado Civil: </label>
                <div class="col-12">
                    <select class="form-control form-control-sm" name="edo_civil">
                        <option value="Soltero(a)" 
                                <?= $alumno->edo_civil == 'Soltero(a)' ? 'checked' : ''; ?>>Soltero(a)</option>
                        <option value="Casado(a)" 
                                <?= $alumno->edo_civil == 'Casado(a)' ? 'checked' : ''; ?>>Casado(a)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">
            <div class="form-group row">
                <label class="col-12">Número Celular: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm"  
                           name="cellphone" 
                           value="<?= $alumno->cellphone; ?>"
                           pattern="[0-9\s]{1,15}">
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="form-group row">
                <label class="col-12">Referencia de Domicilio: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm"  
                           name="reference" 
                           value="<?= $alumno->reference; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group border border-info bg-light p-2">
                <label for="address" class="col-12">Dirección:</label>
                <div class="input-group mb-2">
                    <input type="hidden" 
                          class="form-control form-control-sm" 
                          name="address"
                          value="<?= $alumno->address->id_address; ?>">
                   <input type="text" 
                          class="form-control form-control-sm" 
                          name="street"
                          placeholder="Calle" 
                          value="<?= $alumno->address->street; ?>">
                   <input type="text" 
                          class="form-control form-control-sm" 
                          name="number"
                          placeholder="Número" 
                          value="<?= $alumno->address->st_number; ?>">
                   <input type="text" 
                          class="form-control form-control-sm" 
                          name="between"
                          placeholder="Entre" 
                          value="<?= $alumno->address->st_between; ?>">
                   <input type="text" 
                          class="form-control form-control-sm"  
                          name="colony"
                          placeholder="Colonia" 
                          value="<?= $alumno->address->colony; ?>">
                </div>
                <div class="input-group">
                   <input type="text" 
                          class="form-control form-control-sm" 
                          name="city"
                          placeholder="Ciudad" 
                          value="<?= $alumno->address->city; ?>">
                   <input type="text" 
                          class="form-control form-control-sm" 
                          name="zipcode"
                          placeholder="Código Postal" 
                          value="<?= $alumno->address->zipcode; ?>">
                   <input type="text" 
                          class="form-control form-control-sm" 
                          name="state"
                          placeholder="Estado" 
                          value="<?= $alumno->address->state; ?>">
                   <input type="text" 
                          class="form-control form-control-sm"  
                          name="country"
                          placeholder="País" 
                          value="<?= $alumno->address->country; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label class="col-12">Padecimientos: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm"  
                           name="sickness" 
                           value="<?= $alumno->sickness; ?>">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label class="col-12">Tratamiento: </label>
                <div class="col-12">
                    <input type="tel" 
                           class="form-control form-control-sm"  
                           name="medication" 
                           value="<?= $alumno->medication; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <label class="col-12">¿Es de familia Homestay?: </label>
            <div class=" ml-3 custom-control custom-radio custom-control-inline">
                <input type="radio" 
                       id="inline1" 
                       name="homestay" 
                       class="custom-control-input"
                       <?= $alumno->homestay == '0' ? 'checked' : ''; ?> 
                       value="0">
                <label class="custom-control-label" for="inline1">No</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" 
                       id="inline2" 
                       name="homestay" 
                       class="custom-control-input"
                       <?= $alumno->homestay == '1' ? 'checked' : ''; ?> 
                       value="1">
                <label class="custom-control-label" for="inline2">Si</label>
            </div>
        </div>
        <div class="col-sm-4">
            <label class="col-12">¿Entregó Acta de Nacimiento?: </label>
            <div class="ml-3 custom-control custom-radio custom-control-inline">
                <input type="radio"
                       id="inline3" 
                       name="acta" 
                       class="custom-control-input"
                       <?= $alumno->acta_nacimiento == '0' ? 'checked' : ''; ?> 
                       value="0">
                <label class="custom-control-label" for="inline3">No</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" 
                       id="inline4" 
                       name="acta" 
                       class="custom-control-input"
                       <?= $alumno->acta_nacimiento == '1' ? 'checked' : ''; ?>
                       value="1">
                <label class="custom-control-label" for="inline4">Si</label>
            </div>
        </div>
        <div class="col-sm-4">
            <label class="col-12">¿Requiere Factura?: </label>
            <div class="ml-3 custom-control custom-radio custom-control-inline">
                <input type="radio" 
                       id="inlin51" 
                       name="invoice" 
                       class="custom-control-input"
                       <?= $alumno->facturacion == '0' ? 'checked' : ''; ?>
                       value="0">
                <label class="custom-control-label" for="inline5">No</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" 
                       id="inline6" 
                       name="invoice" 
                       class="custom-control-input"
                       <?= $alumno->facturacion == '1' ? 'checked' : ''; ?>
                       value="1">
                <label class="custom-control-label" for="inline6">Si</label>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="form-group row">
                <label class="col-12">Comentario sobre el alumno(a): </label>
                <div class="col-12">
                    <textarea name="comment" rows="2" class="form-control texto"><?=  $alumno->comment_s;  ?></textarea>
                </div>
            </div>
        </div>  
    </div>

    <div class="row justify-content-center">
        <div class="col-6 text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </div>
</form>