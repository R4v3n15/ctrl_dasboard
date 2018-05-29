<div class="container">
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)">Inicio</a></li>
        <li><a href="javascript:void(0)">Alumnos</a></li>
        <li><a href="javascript:void(0)" class="active">Becados</a></li>
    </ol>    
    
    <div class="well">
        <?php $this->renderFeedbackMessages(); ?>
        <h3 class="text-center text-info">Becados</h3>
        <div class="row">
            <div class="col-sm-4">
                <div class="well card">
                    <div class="card-avatar">
                        <img src="<?php echo Config::get('URL').Config::get('PATH_AVATARS_PUBLIC'); ?>avatar.png" alt="avatar"></div>
                    <div class="card-title"><h3 class="text-center">Avatar Name</h3></div>
                    <div class="card-body"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque sit alias iste quisquam porro odio accusantium placeat libero itaque eius, odit molestias corporis pariatur ullam quibusdam harum nulla impedit labore.</p></div>
                    <div class="card-footer"><label> extra information</label></div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="bs-component">
                    <ul class="nav nav-tabs well line-head" style="margin-bottom: 15px;">
                        <li class="active">
                            <a href="#club" data-toggle="tab">ENGLISH CLUB</a>
                        </li>
                        <li>
                            <a href="#primary" data-toggle="tab">PRIMARY</a>
                        </li>
                        <li>
                            <a href="#adolescent" data-toggle="tab">ADOLESCENTS</a>
                        </li>
                        <li>
                            <a href="#adult" data-toggle="tab">ADULTS</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content well line-body">
                        <?php $this->renderFeedbackMessages(); ?>
                        <div class="tab-pane fade active in" id="club">
                            <h4>Titulo parte 1</h4>
                          <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                        </div>
                        <div class="tab-pane fade" id="primary">
                          <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
                        </div>
                        <div class="tab-pane fade" id="adolescent">
                          <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
                        </div>
                        <div class="tab-pane fade" id="adult">
                          <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>