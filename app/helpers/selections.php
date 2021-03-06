<?php
function getMonths($val ='') {
 
	$data = array(
		"01" => "January",
		"02" => "February",
		"03" => "March",
		"04" => "April",
		"05" => "May",
		"06" => "June",
		"07" => "July",
		"08" => "August",
		"09" => "September",
		"10" => "October",
		"11" => "November",
		"12" => "December"
	);

	return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function civil_status($val ='') {

  $data = array(
    'single'   => 'Single',
    'married'  => 'Married',
    'divorced' => 'Divorced',
    'widowed ' => 'Widowed ',
  );
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function tax_status($val ='') {

  $data = array(
    '0'        => 'Zero Exemption',
    'single'   => 'Single',
    'married'  => 'Married',
  );
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function genders($val ='') {

	$data['male'] = 'Male';
	$data['female'] = 'Female';

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function countries($val ='') {
	$data = (array)json_decode(file_get_contents('data/countries.json'));
	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function job_type($val='') {
	$data = [
		'full_time' => 'Full time',
		'part_time' => 'Part time',
		'temporary' => 'Temporary',
	];

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function payment_method($val='') {
	$data = [
		'cash'           => 'Cash',
		'cheque'         => 'Cheque',
    'bank_transfer'  => 'Bank Transfer',
    'money_order'    => 'Money Order',
    'online_payment' => 'Online Payment',
    'e_wallet'       => 'Electronic wallet',
    'card'           => 'Credit Card / Debit Card',
	];

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function salary_type($val='') {
	$data = [
		'annual' => 'Annual salary',
		'hourly' => 'Hourly',
	];

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function form_actions($val ='') {
 
	$data = array(
		"trash" => "Move to Trash",
	);

	if( Input::get('type') ) {
		$data = array(
			"restore" => "Restore",
			"destroy" => "Delete Permanently",
		);
	}

	return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function order_by($val ='') {

  $data = array(
    'DESC' => 'Descending', 
    'ASC' => 'Ascending',
  );

  return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function user_sort_by($val ='') {

  $data = array(
    'fullname' => 'Name',
    'id' => 'ID', 
  );

  return ($val) ? @$data[$val] : $data;
}


//----------------------------------------------------------------

function user_languages($val='') {
	$languages = json_decode(file_get_contents('data/languages.json'));

	foreach($languages as $lang) {
		$data[$lang->code] = $lang->name;
	}

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function get_times($val='') {

	$data = json_decode(file_get_contents('data/times.json'));

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function status_ico($val) {
	$data['approved'] = '<span class="badge badge-primary uppercase sbold">Approved</span>';

	$data['publish'] = '<span class="badge badge-primary uppercase sbold">Published</span>';

	$data['completed']  = '<span class="badge badge-primary uppercase sbold">Completed</span>';
	$data['pending']    = '<span class="badge badge-danger uppercase sbold">Pending</span>';
	$data["cancelled"]  = '<span class="badge badge-default uppercase sbold">Cancelled</span>';

	$data['unprocessed']  = '<span class="badge badge-danger uppercase sbold">Unprocessed</span>';
	$data["processing"] = '<span class="badge badge-warning uppercase sbold">Processing</span>';
	$data['processed']  = '<span class="badge badge-primary uppercase sbold">Processed</span>';


  $data['paid']     = '<span class="badge badge-primary uppercase sbold">Paid</span>';
  $data["unpaid"]   = '<span class="badge badge-default uppercase sbold">Unpaid</span>';
  $data['partial']  = '<span class="badge badge-warning uppercase sbold">Partial</span>';

  $data['unpay'] = '';
  $data['pay_remaining'] = '';
  $data['pay'] = '';

  $data['delivered']    = '<span class="badge badge-info uppercase sbold">Delivered</span>';
  $data['undelivered']  = '<span class="badge badge-danger uppercase sbold">Undelivered</span>';

	$data['fulfilled']    = '<span class="badge badge-info uppercase sbold">Fulfilled</span>';
  $data['unfulfilled']    = '<span class="badge badge-danger uppercase sbold">Unfulfilled</span>';


	$data["actived"] = '<span class="badge badge-primary uppercase sbold">Actived</span>';
	$data["inactived"] = '<span class="badge badge-default uppercase sbold">Inactived</span>';

	$data["waiting"] = '<span class="badge badge-warning uppercase sbold">Waiting</span>';


	$data["sending"] = '<span class="badge badge-warning uppercase sbold">Sending</span>';
	$data['sent']  = '<span class="badge badge-primary uppercase sbold">Sent</span>';

	$data['draft']  = '<span class="badge badge-success uppercase sbold">Draft</span>';


	$data["YES"] = '<span class="badge badge-info uppercase sbold">YES</span>';
	$data['NO']  = '<span class="badge badge-danger uppercase sbold">NO</span>';

	$data["in"] = '<span class="badge badge-success uppercase sbold">Purchased</span>';
  $data["out"] = '<span class="badge badge-primary uppercase sbold">Sold</span>';

	
	echo $data[$val];
}

//----------------------------------------------------------------


function get_types($key ='', $val='') {
	$data = array(
    "job-type" => array(
      "label"  => "Job Types",
      "single" => "Job Type",
    ),
		"position" => array(
      "label"  => "Positions",
      "single" => "Position",
    ),
    "department" => array(
      "label"  => "Departments",
      "single" => "Department",
    ),
    "shift" => array(
      "label"  => "Shifts",
      "single" => "Shift",
    ),
    "earning" => array(
      "label"  => "Earnings",
      "single" => "Earning",
    ),
	);
	return ($key) ? $data[$key][$val] : $data;
}

//----------------------------------------------------------------

function get_earning_types($key ='', $val='') {
  $data = array(
    "holiday" => array(
      "label"  => "Holidays",
      "single" => "Holiday",
    ),
    "overtime" => array(
      "label"  => "Overtimes",
      "single" => "Overtime",
    ),
    "night" => array(
      "label"  => "Night Differencials",
      "single" => "Night Differencial",
    )
  );
  return ($key) ? $data[$key][$val] : $data;
}

//----------------------------------------------------------------

function user_group($val ='') {
 
	$data = array(
		"admin" => "Admin",
		"user" => "User",
	);

	return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function payment_status($val ='') {
 
  $data = array(
    "paid"    => "Paid",
    "unpaid"  => "Unpaid",
    "partial" => "Partial",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function po_status($val ='') {
 
  $data = array(
    "fulfilled"    => "Fulfilled",
    "unfulfilled"  => "Unfulfilled",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function so_status($val ='') {
 
  $data = array(
    "delivered"    => "Delivered",
    "undelivered"  => "Undelivered",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function metrics($val ='') {
 
  $data = array(
    "bag"     => "Bag(s)",
    "bottle"  => "Bottle(s)",
    "box"     => "Box(es)",
    "can"     => "Can(s)",
    "case"    => "Case(s)",
    "gallon"  => "Gallon(s)",
    "pack"    => "Pack(s)",
    "piece"   => "Piece(s)",
    "sack"    => "Sack(s)",
    "set"     => "Set(s)",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------
