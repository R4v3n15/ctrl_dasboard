<?php if(isset($this->_paginacion)): ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-sm justify-content-end" role="navigation" aria-label="Pagination">
            <?php if($this->_paginacion['primero']): ?>
                <li class="page-item" title="Primero">
                    <a class="page-link <?= $classpagination;?>" 
                       <?= $datattr;?>="<?php echo $this->_paginacion['primero']; ?>" 
                       href="javascript:void(0);">&Lt;</a>
                </li>
            <?php else: ?>
                <li class="page-item pagination-previous disabled" title="Primero">
                    <span class="page-link">&Lt;</span>
                </li>
            <?php endif; ?>


            <?php if($this->_paginacion['anterior']): ?>
                <li class="page-item" title="Anterior">
                    <a  class="page-link <?= $classpagination;?>" 
                        <?= $datattr;?>="<?php echo $this->_paginacion['anterior']; ?>" 
                        href="javascript:void(0);"><span>&lt;</span></a>
                </li>
            <?php else: ?>
                <li class="page-item pagination-previous disabled" title="Anterior">
                    <span class="page-link">&lt;</span>
                </li>
            <?php endif; ?>


            <?php for($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>

                <?php if($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>
                    <li class="page-item active" title="Actual">
                        <span class="page-link"><?php echo $this->_paginacion['rango'][$i]; ?></span>
                    </li>
                <?php else: ?>
                    <li class="page-item" title="Pagina: <?= $this->_paginacion['rango'][$i] ?>">
                        <a  class="page-link <?= $classpagination;?>" 
                            <?= $datattr;?>="<?php echo $this->_paginacion['rango'][$i]; ?>" 
                            href="javascript:void(0);">
                            <?php echo $this->_paginacion['rango'][$i]; ?>
                        </a>
                    </li>
                <?php endif; ?>

            <?php endfor; ?>


            <?php if($this->_paginacion['siguiente']): ?>

                <li class="page-item" title="Siguiente">
                    <a  class="page-link <?= $classpagination;?>" 
                        <?= $datattr;?>="<?php echo $this->_paginacion['siguiente']; ?>" 
                        href="javascript:void(0);"<span>&gt;</span></a>
                </li>

            <?php else: ?>
                <li class="page-item pagination-next disabled" title="Siguiente">
                    <span class="page-link">&gt;</span>
                </li>
            <?php endif; ?>

            <?php if($this->_paginacion['ultimo']): ?>

                <li class="page-item" title="Ultimo">
                    <a  class="page-link <?= $classpagination;?>" 
                        <?= $datattr; ?>="<?php echo $this->_paginacion['ultimo']; ?>" 
                        href="javascript:void(0);">&Gt;</a>
                </li>

            <?php else: ?>
                <li class="page-item pagination-next disabled" title="Ultimo"><span class="page-link">&Gt;</span></li>
            <?php endif; ?>

        </ul>
    </nav>
<?php endif; ?>