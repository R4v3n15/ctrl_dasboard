<?php

// get the feedback (they are arrays, to make multiple positive/negative messages possible)
$feedback_positive = Session::get('feedback_positive');
$feedback_negative = Session::get('feedback_negative');

// echo out positive messages
if (isset($feedback_positive)) {
    foreach ($feedback_positive as $feedback) {
        echo '<div class="bs-component">
	            <div class="alert alert-dismissible bg-success text-white">
	                <button type="button" class="close" data-dismiss="alert">&times;</button>
	                '.$feedback.'
	            </div>
	        </div>';
    }
}

// echo out negative messages
if (isset($feedback_negative)) {
    foreach ($feedback_negative as $feedback) {
        echo '<div class="bs-component">
	            <div class="alert alert-dismissible bg-danger text-white">
	                <button type="button" class="close" data-dismiss="alert">&times;</button>
	                '.$feedback.'
	            </div>
	        </div>';
    }
}
