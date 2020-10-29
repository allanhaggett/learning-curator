<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Step $step
 */
$this->layout = 'nowrap';
$this->loadHelper('Authentication.Identity');
$uid = 0;
$role = 0;
if ($this->Identity->isLoggedIn()) {
	$role = $this->Identity->get('role_id');
	$uid = $this->Identity->get('id');
}
/** 
 * Most of the following should be moved into the controller
 * I just find it easier to prototype when the logic I'm working
 * with is in the same file
 */
$stepTime = 0;
$defunctacts = array();
$requiredacts = array();
$supplementalacts = array();
$acts = array();

$readstepcount = 0;
$watchstepcount = 0;
$listenstepcount = 0;
$participatestepcount = 0;
$allreadstepcount = 0;
$allwatchstepcount = 0;
$alllistenstepcount = 0;
$allparticipatestepcount = 0;
$readcolor = '';
$watchcolor = '';
$listencolor = '';
$participatecolor = '';
$stepactivitylist = '';

$totalacts = count($step->activities);
$stepclaimcount = 0;

foreach ($step->activities as $activity) {
	$stepname = '';
	//print_r($activity);
	// If this is 'defunct' then we pull it out of the list 
	// and add it the defunctacts array so we can show them
	// but in a different section
	if($activity->status_id == 3) {
		array_push($defunctacts,$activity);
	} elseif($activity->status_id == 2) {
		// if it's required
		if($activity->_joinData->required == 1) {
			$stepactivitylist .= $activity->id . '-' . $activity->activity_types_id . ',';
			array_push($requiredacts,$activity);
			if($activity->activity_types_id == 1) {
				$watchstepcount++;
			} elseif($activity->activity_types_id == 2) {
				$readstepcount++;
			} elseif($activity->activity_types_id == 3) {
				$listenstepcount++;
			} elseif($activity->activity_types_id == 4) {
				$participatestepcount++;
			}
		// Otherwise it's teriary
		} else {
			array_push($supplementalacts,$activity);
		}
		array_push($acts,$activity);
		

		if($activity->activity_types_id == 1) {
			$allwatchstepcount++;
			$watchcolor = $activity->activity_type->color;
		} elseif($activity->activity_types_id == 2) {
			$allreadstepcount++;
			$readcolor = $activity->activity_type->color;
		} elseif($activity->activity_types_id == 3) {
			$alllistenstepcount++;
			$listencolor = $activity->activity_type->color;
		} elseif($activity->activity_types_id == 4) {
			$allparticipatestepcount++;
			$participatecolor = $activity->activity_type->color;
		}

		$tmp = array();
		// Loop through the whole list, add steporder to tmp array
		foreach($requiredacts as $line) {
			$tmp[] = $line->_joinData->steporder;
		}
		// Use the tmp array to sort acts list
		array_multisort($tmp, SORT_DESC, $requiredacts);
		//array_multisort($tmp, SORT_DESC, $supplementalacts);
	}
}

$pagetitle = $step->name . ' - ' . $step->pathways[0]->name;
$this->assign('title', h($pagetitle));
$stepacts = count($requiredacts);
$supplmentalcount = count($supplementalacts);
$completeclass = 'notcompleted'; 
if($stepclaimcount == $totalacts) {
	$completeclass = 'completed';
}

if($stepclaimcount > 0) {
	$steppercent = ceil(($stepclaimcount * 100) / $stepacts);
} else {
	$steppercent = 0;
}



?>
<style>
.dotactive {
	color: #000;
    font-size: 140%;
}
.dot {
	color: #000;
}

</style>
<div class="container-fluid">
<div class="row justify-content-md-center" id="colorful">
<div class="col-md-8">
<?php if (!empty($step->pathways)) : ?>
<?php if($role == 2 || $role == 5): ?>
<div class="btn-group float-right mt-3 ml-3">
<?= $this->Html->link(__('Edit'), ['controller' => 'Steps', 'action' => 'edit', $step->id], ['class' => 'btn btn-light btn-sm']) ?>
<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $step->id],['class' => 'btn btn-light btn-sm', 'confirm' => __('Are you sure you want to delete # {0}?', $step->name)]) ?>
</div> <!-- /.btn-group -->
<?php endif ?>

<?php foreach ($step->pathways as $pathways) : ?>
<?php $totalsteps = count($pathways->steps) ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb mt-3">
  	<li class="breadcrumb-item"><?= $pathways->has('category') ? $this->Html->link($pathways->category->name, ['controller' => 'Categories', 'action' => 'view', $pathways->category->id]) : '' ?></li>
	<li class="breadcrumb-item"><a href="/learning-curator/pathways/view/<?= $pathways->id ?>"><?= h($pathways->name) ?></a></li>
	<!--<li class="breadcrumb-item" aria-current="page"><?= h($pathways->steps[0]->name) ?> </li>-->
  </ol>
</nav> 

<!--<div class="float-right"><a href="/learning-curator/pathways/path/<?= $pathways->id ?>"><i class="fas fa-scroll"></i></a></div>-->
<h1>
	<?= h($pathways->name) ?>
	<?php //$this->Html->link(h($pathways->name), ['controller' => 'Pathways', 'action' => 'path', $pathways->id]) ?>
</h1>

<!--<?= $this->Text->autoParagraph(h($pathways->objective)); ?>-->

<?php foreach($pathways->steps as $s): ?>
<?php $c = ''; ?>
<?php $pagetitle = ''; ?>
<?php $n = next($pathways->steps) ?>
<?php if($s->id == $step->id): ?>

<div class="row mx-0">
	<div class="col" style="background-color: rgba(255,255,255,.5); border-radius: .25rem;">
		<h2 class="mt-2">
			<?= $s->name ?> 
			<?php if($steppercent == 100): ?>
				<i class="fas fas fa-check-circle"></i>
			<?php endif ?>
			<!--<small><span class="badge badge-dark"><?= $totalsteps ?></span> total steps</small>-->
		</h2>	
		<div class="" style="font-size: 130%;"><?= $s->description; ?></div>
		<div class="my-3">
			<span class="badge rounded-pill bg-light text-dark"><?= $totalacts ?> total activities</span> 
			<span class="badge rounded-pill bg-light text-dark"><?= $stepacts ?> required</span>
			<span class="badge rounded-pill bg-light text-dark"><?= $supplmentalcount ?> supplemental</span>
			<span class="badge rounded-pill bg-light text-dark" style="background-color: rgba(<?= $readcolor ?>,1)">
				<?= $allreadstepcount ?> to read
			</span>  
			<span class="badge rounded-pill bg-light text-dark" style="background-color: rgba(<?= $watchcolor ?>,1)">
				<?= $allwatchstepcount ?> to watch
			</span>  
			<span class="badge rounded-pill bg-light text-dark" style="background-color: rgba(<?= $listencolor ?>,1)">
				<?= $alllistenstepcount ?> to listen to
			</span>  
			<span class="badge rounded-pill bg-light text-dark" style="background-color: rgba(<?= $participatecolor ?>,1)">
				<?= $allparticipatestepcount ?> to participate in
			</span>  
		</div>
	</div>
	<div class="col-2">
		<?php if(!empty($laststep)): ?>
		<a href="/learning-curator/steps/view/<?= $laststep ?>" style="color: #000; font-size: 250%;"><i class="fas fa-arrow-circle-left"></i></a>
		<?php endif ?>

		<?php if(!empty($n->id)): ?>
		<a href="/learning-curator/steps/view/<?= $n->id ?>" class="nextstep" style="color: #000; font-size: 250%; float: right;"><i class="fas fa-arrow-circle-right"></i></a>
		<?php endif ?>
		
	</div>
</div>

<?php endif ?>
<?php 
$laststep = $s->id;
$lastname = $s->name;
$lastobj = $s->description;
?>
<?php endforeach ?>
<div class="my-3">
<?php $count = 1 ?>
<?php foreach($pathways->steps as $s): ?>
	<?php $c = 'dot' ?>
	<?php if($s->id == $step->id) $c = 'dotactive' ?>
	<a href="/learning-curator/steps/view/<?= $s->id ?>">
		<i class="fas fa-dot-circle <?= $c ?>" title="Step <?= $count ?>"></i>
	</a>
<?php $count++ ?>
<?php endforeach ?>
</div>
</div>
</div>
<?php endforeach; ?>

<?php endif; ?>




</div>

<div class="progress progress-bar-striped stickyprogress" style="background-color: #F1F1F1; border-radius: 0; height: 18px;">
		<div class="progress-bar bg-dark" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
		This step is 0% done
	  </div>
</div>

<div class="container-fluid linear pt-3">
<div class="row justify-content-md-center">

<div class="col-md-6 col-lg-6">
<?php if (!empty($step->activities)) : ?>

<?php foreach ($requiredacts as $activity) : ?>

<div class="bg-white rounded-lg">
<div class="p-3 mb-3 rounded-lg activity" 
		style="background-color: rgba(<?= $activity->activity_type->color ?>,.2);">
	























	<div class="activity" id="activity-<?= $activity->id ?>">
		<?php $idandtype = $activity->id . '-' . $activity->activity_type->id ?>
		<a href="#" class="btn btn-dark btn-lg" id="followme" onclick="return claimit('<?= $idandtype ?>')">Claim <i class="far fa-check-circle"></i></a>
	</div>























	<h3 class="my-3">
		<a href="/learning-curator/activities/view/<?= $activity->id ?>"><?= $activity->name ?></a>
		<!--<a class="btn btn-sm btn-light" href="/learning-curator/activities/view/<?= $activity->id ?>"><i class="fas fa-angle-double-right"></i></a>-->
	</h3>
	<div class="p-3" style="background: rgba(255,255,255,.3);">
		<div class="mb-3">
		<span class="badge rounded-pill bg-light text-dark" data-toggle="tooltip" data-placement="bottom" title="This activity should take <?= $activity->estimated_time ?> to complete">
			<i class="fas fa-clock"></i>
			<?php echo $this->Html->link($activity->estimated_time, ['controller' => 'Activities', 'action' => 'estimatedtime', $activity->estimated_time]) ?>
		</span> 
		<?php foreach($activity->tags as $tag): ?>
		<a href="/learning-curator/tags/view/<?= h($tag->id) ?>" class="badge rounded-pill bg-light text-dark"><?= $tag->name ?></a> 
		<?php endforeach ?>
		</div>

		<?= $activity->description ?>

	</div>
	
	<?php if(!empty($activity->tags)): ?>
	<?php foreach($activity->tags as $tag): ?>

	<?php if($tag->name == 'Learning System Course'): ?>

	<a target="_blank" 
		rel="noopener" 
		data-toggle="tooltip" data-placement="bottom" title="Enrol in this course in the Learning System"
		href="https://learning.gov.bc.ca/psc/CHIPSPLM_6/EMPLOYEE/ELM/c/LM_OD_EMPLOYEE_FL.LM_FND_LRN_FL.GBL?Page=LM_FND_LRN_RSLT_FL&Action=U&KWRD=<?php echo urlencode($activity->name) ?>" 
		style="background-color: rgba(<?= $activity->activity_type->color ?>,1); color: #000; font-weight: bold;" 
		class="btn btn-block my-3 text-uppercase btn-lg">

			<i class="fas <?= $activity->activity_type->image_path ?>"></i>

			<?= $activity->activity_type->name ?>

	</a>

	<?php elseif($tag->name == 'YouTube'): ?>
	<?php 
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $activity->hyperlink, $match);
		$youtube_id = $match[1];
		?>
	<div class="my-3 p-3" style="background-color: rgba(<?= $activity->activity_type->color ?>,1); border-radius: 3px;">
		<iframe width="100%" 
			height="315" 
			src="https://www.youtube-nocookie.com/embed/<?= $youtube_id ?>/" 
			frameborder="0" 
			allow="" 
			allowfullscreen>
		</iframe>
	</div>
	<?php elseif($tag->name == 'Vimeo'): ?>
		<?php 
		$vimeoid = substr(parse_url($activity->hyperlink, PHP_URL_PATH), 1);
		?>
		<iframe src="https://player.vimeo.com/video/<?= $vimeoid ?>" 
			width="100%" 
			height="315" 
			frameborder="0" 
			allow="fullscreen" 
			allowfullscreen>
		</iframe>
		
	<?php endif; // logic check for formatting differently based on tag ?>	

	<?php endforeach; // tags loop ?>

	<?php else: // if there aren't any tags at all, default ?>


	<a target="_blank" 
		rel="noopener" 
		data-toggle="tooltip" data-placement="bottom" title="Launch this activity"
		href="<?= $activity->hyperlink ?>" 
		style="background-color: rgba(<?= $activity->activity_type->color ?>,1); color: #000; font-weight: bold;" 
		class="btn btn-block my-3 text-uppercase btn-lg">

			<i class="fas <?= $activity->activity_type->image_path ?>"></i>

			<?= $activity->activity_type->name ?>

	</a>

	<?php endif; // are there tags? ?>	

	</div>
	</div> <!-- whitebg -->

	<?php endforeach; // end of activities loop for this step ?>

<?php endif; ?>

<?php if(count($supplementalacts) > 0): ?>

	<h3>Supplementary Resources</h3>
	<div class="row">
	<?php foreach ($supplementalacts as $activity): ?>
	<div class="col-md-12 col-lg-12">
	<div class="p-3 my-3 bg-white rounded-lg">

		<h4>
			<a href="/learning-curator/activities/view/<?= $activity->id ?>">
				<?= $activity->name ?>
			</a>
		</h4>
		<div class="p-2">
			<div>
				<span class="badge rounded-pill bg-light text-dark" data-toggle="tooltip" data-placement="bottom" title="This activity should take <?= $activity->estimated_time ?> to complete">
					<i class="fas fa-clock"></i>
					<?php echo $this->Html->link($activity->estimated_time, ['controller' => 'Activities', 'action' => 'estimatedtime', $activity->estimated_time]) ?>
				</span> 
			</div>
			<?= $activity->description ?>
			
			<a target="_blank" 
				rel="noopener" 
				data-toggle="tooltip" data-placement="bottom" title="Launch this activity"
				href="<?= $activity->hyperlink ?>" 
				style="background-color: rgba(<?= $activity->activity_type->color ?>,1); color: #000; font-weight: bold;" 
				class="btn btn-block my-3 text-uppercase btn-lg">

					<i class="fas <?= $activity->activity_type->image_path ?>"></i>

					<?= $activity->activity_type->name ?>

			</a>
		</div>

	</div>
	</div>
	<?php endforeach; // end of activities loop for this step ?>
</div>

<?php endif ?>
</div>
<div class="col-8 col-md-3 col-lg-2">

























<!-- acitivity rings go here -->
<div id="paths" style="display: none">
	<a href="#" class="btn btn-dark btn-block btn-lg" id="followme" onclick="return followit()">Follow</a>
	<div>
		Following a pathway is a commitment to moving 
		through each step and claiming each required activity as you complete it.
		Fill your activity rings and get a certificate!
	</div>
</div>
<div class="p-3 bg-white rounded-lg">
<canvas id="activityrings" width="250" height="250"></canvas>
</div>





















</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/pouchdb@7.2.1/dist/pouchdb.min.js"></script>
<script>
	//
	// Initialize activity ring load on page load
	//
	//loadStatus();

	// A list of all activity IDs from the *pathway*
	// We use this list when we build the activity rings
	var pathallactivities = '<?= rtrim($pathallactivities,',') ?>';

	// A list of all activity IDs from this step
	// We use this list when we compare it with IDs 
	// that are listed in the localstore 
	var stepactivitylist = '<?= rtrim($stepactivitylist,',') ?>';

	// The PHP generated the comma-separated list
	// now split into an array
	var acts = pathallactivities.split(',');

	// The pathway ID of this step
	var pathwayid = <?= $pathid ?>;

	// Open the localstore database
	// #TODO sync this a remote database!
	// If we're planning on synching this to a remote, that 
	// this is where I'm going to absolutely need a session/unique id 
	// variable to create a new database for each user; otherwise, 
	// everyone is writing to the same datbase and if I claim something
	// it's now claimed for you too.
	// If we're going to create a unique DB for each user, we still
	// need the unique ID as we'll have to store the value with 
	// each entry and modify below not use a query instead of 
	// db.allDocs()
	var db = new PouchDB('curator-ta'); // http://localhost:5984/

	// Start looping through each item in the localstore
	// A record will either be a pathway or an activity
	// so we just perform a simple check and update the UI 
	// accordingly
	var progress = 0;

	var watchcolor = '<?= $watchcolor ?>';
	var readcolor = '<?= $readcolor ?>';
	var listencolor = '<?= $listencolor ?>';
	var participatecolor = '<?= $participatecolor ?>';

	var watchcount = 0;
	var readcount = 0;
	var listencount = 0;
	var participatecount = 0;

	//
	// Loop through every row in the database and look at each;
	// with a little logic, we build it all :) 
	//
	db.allDocs({include_docs: true, descending: true}, function(err, doc) {

		doc.rows.forEach(function(e,index){

			//
			// Activities
			// Take the list of all activities on this step and 
			// break it in an array. Then loop through said array
			// and compare each of the IDs against the ID from our
			// localstore. If there's a match, then update the claim
			// button to indicate you've already claimed.
			// While we're looping through, we also build up the 
			// var necessary to show the activity rings 
			// #TODO implement unclaim
			// 
			acts.forEach(function(item, index, arr) {
				if(e.doc['activity'] === item) {
					let idandtype = item.split('-');
					let iid = 'activity-' + idandtype[0];
					if(document.getElementById(iid)) {
						let newbutton = '<span class="btn btn-dark btn-lg">';
						newbutton += 'Claimed ';
						newbutton += '<i class="fas fa-check-circle">';
						newbutton += '</span>';
						document.getElementById(iid).innerHTML = newbutton;
					}
					if(idandtype[1] == 1) {
						watchcount++;
					} else if(idandtype[1] == 2) {
						readcount++;
					} else if(idandtype[1] == 3) {
						listencount++;
					} else if(idandtype[1] == 4) {
						participatecount++;
					}
					progress++;
				}
			});
			
			//
			// Pathways
			// Compare the ID provided in the markup to the 
			// ID in the localstore. If we're following this
			// pathway, then update the UI to say so, otherwise
			// we just show the default follow button that's 
			// already in the markup
			if(e.doc['pathway'] == pathwayid) {
				document.getElementById("paths").innerHTML = '<h1>Following!</h1>';
			} 

		}); // end of db.allDocs()

		var allwatch = <?= $allwatch ?>;
		var allread = <?= $allread ?>;
		var alllisten = <?= $alllisten ?>;
		var allparticipate = <?= $allparticipate ?>;

		var totalacts = acts.length;
		var percent = (Number(progress) * 100) / Number(totalacts);
		var percentleft = 100 - percent;

		var watchpercent = (Number(watchcount) * 100) / Number(allwatch);
		var watchpercentleft = 100 - watchpercent;

		console.log('Total activities: ' + totalacts);
		console.log('Activities claimed: ' + progress);
		if(percent > 0) {
			console.log('Percent done: ' + Math.ceil(percent));
			console.log('Percent left: ' + Math.ceil(percentleft));
		}
		if(watchpercent > 0) {
			console.log('Watch Percent done: ' + Math.ceil(watchpercent));
			console.log('Watch Percent left: ' + Math.ceil(watchpercentleft));
		}
		document.getElementById("paths").style.display = 'block';

		var chartdata = {"datasets": [
				{"data": [53,47],"backgroundColor": ["rgba(249,145,80,1)","rgba(249,145,80,.2)"]},
				{"data": [Math.ceil(watchpercent),Math.ceil(watchpercentleft)],"backgroundColor": ["rgba(193,129,183,1)","rgba(193,129,183,.2)"]},
				{"data": [50,50],"backgroundColor": ["rgba(244,105,115,1)","rgba(244,105,115,.2)"]},
				{"data": [37,63],"backgroundColor": ["rgba(255,218,96,1)","rgba(255,218,96,.2)"]}
		]};

		var ctx = document.getElementById('activityrings').getContext('2d');
		var myDoughnutChart = new Chart(ctx, {
			type: 'doughnut',
			data: chartdata,
			options: { 
				legend: { 
					display: false 
				},
			}
		});



	});

	//
	// When the user clicks on the "Follow this pathway" button
	// this function fires and inserts the ID for the pathway
	// into the localstore
	//
	function followit () {		
		rightnow = new Date().getTime();
		var doc = {
			"_id": rightnow.toString(),
			"date": rightnow.toString(),
			"pathway": pathwayid,
		};
		db.put(doc);
		document.getElementById("paths").innerHTML = '<h1>Following!</h1>';
		return false;
	};

	//
	// When the user clicks on the "Claim" button
	// this function fires and inserts the ID for 
	// activity into the localstore.
	// We also update the UI immediately to indicate the claim.
	// We are encoding both the activity ID and the its 
	// associated activity type ID so that we can properly
	// build the activity rings on each page 
	//
	function claimit (activityid) {	

		// use a simple timestamp as the id	
		rightnow = new Date().getTime();
		var doc = {
			"_id": rightnow.toString(),
			"date": rightnow.toString(),
			"activity": activityid,
		};
		db.put(doc);

		// Now that we've put the activityid-activitytype code
		// into the localstore, let's separate out the actual
		// activity id from the activity type
		var idandtype = activityid.split('-');
		var iid = 'activity-' + idandtype[0];
		newbutton = '<span class="btn btn-dark btn-lg">';
		newbutton += 'Claimed ';
		newbutton += '<i class="fas fa-check-circle"></i>';
		newbutton += '</span> ';
		newbutton += 'View all of your claims on <a href="#">your dashboard</a>';
		document.getElementById(iid).innerHTML = newbutton;
		return false;
	};
		










function loadStatus() {
	
	var chartdata = {"datasets": [
				{"data": [53,47],"backgroundColor": ["rgba(249,145,80,1)","rgba(249,145,80,.2)"]},
				{"data": [75,25],"backgroundColor": ["rgba(193,129,183,1)","rgba(193,129,183,.2)"]},
				{"data": [50,50],"backgroundColor": ["rgba(244,105,115,1)","rgba(244,105,115,.2)"]},
				{"data": [37,63],"backgroundColor": ["rgba(255,218,96,1)","rgba(255,218,96,.2)"]}
	]};

	var ctx = document.getElementById('activityrings').getContext('2d');
	var myDoughnutChart = new Chart(ctx, {
		type: 'doughnut',
		data: chartdata,
		options: { 
			legend: { 
				display: false 
			},
		}
	});

}












	
	// //Creating remote database object
	// 
	
	// var remoteDB = new PouchDB('http://localhost:5984/curator-ta');

	// //Synchronising Remote and local databases
	// db.sync(remoteDB, function(err, response) {
	// 	if (err) {
	// 		return console.log(err);
	// 	} else {
	// 		console.log(response);
	// 	}
	// });
</script>