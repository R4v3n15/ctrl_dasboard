<div class="container">
    <ol class="breadcrumb">
          <li><a href="javascript:void(0)" class="active">Principal</a></li>
    </ol>    
    <div class="well">
        <div class="row">
            <div class="col-md-12">
                <?php $this->renderFeedbackMessages(); ?>
            </div>
            <div class="col-md-12">
                <?php $cont=1; foreach ($this->repetidos as $row): ?>
                    <p>=  =  =  =  =  =  = <?= $cont; ?> =  =  =  =  =  =  =</p>
                    <ul>
                        <?php foreach ($row->items as $item): ?>
                            <li>
                                <span id="<?=$item->id_student; ?>message"></span>
                                <?= $item->id_student .' '. ' | Est: '. $item->status . ' | Tut: '. $item->id_tutor.'<br>'; ?>
                                <input type="text" id="<?=$item->id_student; ?>name" value="<?= $item->surname1_s; ?>">
                                <input type="text" id="<?=$item->id_student; ?>surname" value="<?= $item->surname2_s; ?>">
                                <input type="text" id="<?=$item->id_student; ?>lastname" value="<?= $item->name_s; ?>">
                                <button type="button" 
                                        class="btn btn-sm btn-info btn_update" 
                                        id="<?=$item->id_student; ?>"><i class="fa fa-save"></i></button>
                            </li>
                        <?php endforeach ?>
                    </ul>
                    <?php $cont++; ?>
                <?php endforeach ?>
            </div>
            <div class="col-md-12 text-center">
                <?php  
                    if ($this->paginacion) {
                        echo $this->paginacion;
                    }
                ?>
            </div>
        </div>
    </div>
</div>


