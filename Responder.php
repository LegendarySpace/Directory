<?php
    session_start();
    // when logged in store userID in session variable
    if (empty($_SESSION['userID'])) $_SESSION['userID'] = 'NONE';

    $servername = "sql306.epizy.com";
    $username = "epiz_25453908";
    $password = "SfKHOiolSYO3Yh";
    $dbname = "epiz_25453908_Directory";
    function sendError($str) {
        http_response_code(500);
        echo "{$str}\n" ?? "Item not found\n";
        die();
    }
    function sendData($data) {
		header("Content-Type: application/json");
        $json = json_encode($data);
		if ($json === false) {
		    // Avoid echo of empty string (which is invalid JSON), and
		    // JSONify the error message instead:
		    $json = json_encode(["jsonError" => json_last_error_msg()]);
		    if ($json === false) {
		        // This should not happen, but we go all the way now:
		        $json = '{"jsonError":"unknown"}';
		    }
		    // Set HTTP response status code to: 500 - Internal Server Error
		    http_response_code(500);
		}
        echo isset($_GET['callback'])
            ? "{$_GET['callback']}($json)"
            : $json;
    }
    function generateID($size = 12) {
        // This will generate the random alphanumeric IDs
        $id = '';
        $alphanumeric = array_merge(range('a','z'), range(0,9), range('A', 'Z'));
        for($i=0; $i < $size; $i++) {
            // Generate random number between 0 and 61. 0-25 = [a-z], 26-35 = [0-9], 36-61 = [A-Z]
            $randDigit = rand(0, 61);
            $id = "{$id}{$alphanumeric[$randDigit]}";
        }
        return $id;
    }
    function hasID($obj) {
        if (empty($obj) || $obj['id'] === null) return false;
        else return true;
    }
    function getID($obj, $connect, $table) {
        if(empty($obj)||empty($table)) return false;
        // If it has an id use that instead to fill the others TODO
        // Convert aux to table column based on what table is used
        $auxArray = array('Towers'=>'Location','Companies'=>'Reception','Events'=>'Host','Employees'=>'Title','Users'=>'Password');
        $sql = "SELECT AccountID FROM {$table} WHERE Name='{$obj['name']}' AND {$auxArray[$table]}='{$obj['aux']}'";
        if($table === 'Employees') {
            $name = str_split("/[\s,]+/", $obj['name']);
            $sql = "SELECT AccountID FROM {$table} WHERE FirstName='{$name[0]}' AND LastName='{$name[1]}' AND {$auxArray[$table]}='{$obj['aux']}'";
        }
        if(hasID($obj)) $sql = "SELECT * FROM {$table} WHERE AccountID={$obj['id']}";
        $result = $connect->query($sql);
        if($result->num_rows > 0) {
            // TODO Update to do deeper search, currently returns the first one it finds
            $row = $result->fetch_assoc();
            if (hasID($obj)) {$obj['name'] = $row['name']; $obj['aux'] = $row[$auxArray[$table]];}
            else $obj['id'] = $row['AccountID'];
            return $row['AccountID'];
        }
        return false;
    }

    /**
     *  GET Structure
     *  three cases get is called
     *      get splash data
     *          input must contain page and may contain name, aux
     *      get tile data
     *          input must contain section and may contain tname, taux && cname, caux
     *      get bubble data
     *          input must contain section, name, aux and may contain tname, taux && cname, caux
    **/

    if($_SERVER["REQUEST_METHOD"] == "GET") {
        // check for frame data first

        $purpose = $_GET["purpose"];
        if(!empty($purpose)) {
            // Open connection to database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                sendError("Error connecting to SQL: ".$conn->connect_error);
                die("Connection Failed: " . $conn->connect_error);
            }
            if (isset($_GET['id'])) $id = $_GET['id'];
            
            switch ($purpose) {
                case 'form':
                    // return form content
                    $context = $_GET['context'];
                    $content = null;
                    // switch on context [login, register, item[]]
                    switch ($context) {
                        case 'login':
                            $content = array("username"=>'',"password"=>'');
                            break;
                        case 'register':
                            $main = array("first name"=>null,"last name"=>null,"username"=>null,"password"=>null);
                            $tower = array("name"=>null,"location"=>null,"management company"=>null,"number"=>null,"email"=>null,"image"=>null,"details"=>null);
                            $company = array("name"=>null,"suite"=>array(),"reception"=>null,"number"=>null,"email"=>null,"slogan"=>null,"image"=>null,"details"=>null);
                            $content = array('main'=>$main,'tower'=>$tower,'company'=>$company);
                            break;
                        case 'event':
                            $content = array("name"=>null,"host"=>null,"company"=>null,"tower"=>null,"slogan"=>null,"location"=>null,"details"=>null);
                            break;
                        case 'employee':
                            $content = array("first name"=>null,"last name"=>null,"title"=>null,"phone"=>null,"email"=>null,"address"=>null,"public"=>false,"image"=>null);
                            break;

                    }
                    if (empty($content)) sendError("Error Loading Content");
                    else sendData($content);
                    break;

                case 'splash':
                    // Set universal variables
                    $page = $_GET['page'];
                    $splashArray = $sectionsArray = $admin = null;
                    $admin = false;
                    // if logged in retrieve id
                    switch($page) {
                        case 'landing':
                            // Accepts no input
                            // get adminID
                            // $admin = $_SESSION['userID'] === adminID
                            // initialize splash message
                            $splashArray = array('message'=>'Welcome to Faux Directory', 'sub'=>'Surprisingly real');
                            // add sections
                            $sectionsArray = array('Tower', 'Event');
                            break;
                        case 'tower':
                            $sql = "SELECT * FROM Towers WHERE AccountID='{$id}'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                // Only grab the first item, assign items to $splashArray
                                $row = $result->fetch_assoc();
	                            // $admin = $_SESSION['userID'] === $row['AdminID'];
                                $splashArray = array('name'=>$row['Name'], 'location'=>$row['Location'], 'manage'=>$row['ManagementCompany'],
                                    'phone'=>$row['ManagementContact'], 'email'=>$row['ManagementContactEmail'],
                                    'details'=>$row['Details'], 'img'=>$row['ImageURL']);
                            }
                            $sectionsArray = array('Company', 'Event', 'Employee');
                            break;
                        case 'company':
                            $sql = "SELECT * FROM Companies WHERE AccountID='{$id}'";
	                        $result = $conn->query($sql);
	                        if($result->num_rows > 0) {
	                            // Only grab the first item, assign items to $splashArray
	                            $row = $result->fetch_assoc();
	                            // $admin = $_SESSION['userID'] === $row['AdminID'];
	                            $splashArray = array('name'=>$row['Name'], 'suite'=>$row['Suite'], 'reception'=>$row['Reception'],
	                                'phone'=>$row['ContactNumber'], 'email'=>$row['ContactEmail'], 'slogan'=>$row['Slogan'],
	                                'details'=>$row['Details'], 'img'=>$row['ImageURL']);
	                        }
	                        $sectionsArray = array('Event', 'Employee');
                            break;
                        default:
                    }
                    //  if any are empty send error
                    if (empty($splashArray) || empty($sectionsArray) || $admin === null) sendError("Error Loading Splash");
                    else sendData(array('splash'=>$splashArray, 'sections'=>$sectionsArray, 'admin'=>false));
                    break;

                case 'tiles':
                    // Set universal variables
                    $section = $_GET['section'];
                    $tileArray = array();
                    $additional = null;
                    // Get IDs if set, can be null
                    $tower = (!empty($_GET['tower']))? $_GET['tower']: null;
                    $company = (!empty($_GET['company']))? $_GET['company']: null;

                    // switch through section
                    switch ($section) {
                        case 'tower':
                            $sql = 'SELECT AccountID, Name, Location FROM Towers';
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row= $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'],'aux'=>$row['Location'],'id'=>$row['AccountID']));
                                }
                            }
                            break;
                        case 'company':
                            if($id) $additional = "TowerID='{$id}'";

                            $sql = 'SELECT AccountID, Name, Reception FROM Companies';
                            if ($additional) $sql = "{$sql} WHERE {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'], 'aux'=>$row['Reception'],'id'=>$row['AccountID']));
                                }
                            }
                            break;
                        case 'event':
                            // if !empty(towerName) set $additional to 'TowerID=$towerid'
                            // if !empty(companyName) if !empty($additional) use $additional with %companyid, set $additional to 'CompanyID=$companyid'
                            if($tower) $additional = "TowerID='{$tower}'";
                            if($company) $additional = (empty($additional))? "CompanyID='{$company}'": "{$additional} AND CompanyID='{$company}'";

                            $sql = "SELECT AccountID, Name, Host FROM Events WHERE AccountID={$id}";
                            if(!empty($additional)) $sql = "{$sql} WHERE {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'], 'aux'=>$row['Host'],'id'=>$row['AccountID']));
                                }
                            }
                            break;
                        case 'employee':
                            // $tower and $company are mutually exclusive
                            // if !empty(companyName) set $additional to 'CompanyID=$companyid'
                            // if !empty(towerName) set $additional to each company with 'TowerID=$towerid'
                            if ($company) $additional = "CompanyID='{$company}'";
                            elseif ($tower) {
                                // For loop through companies adding them to additional
                                $query = "SELECT AccountID FROM Companies WHERE TowerID='{$tower}'";
                                $res = $conn->query($query);
                                if($res->num_rows > 0) {
                                    // Set additional to contain all companies
                                    while ($row = $res->fetch_assoc()) {
                                        $additional = (empty($additional))? "CompanyID='{$row['AccountID']}'": "{$additional} OR CompanyID='{$row['AccountID']}'";
                                    }
                                }
                            }

                            // TODO Employees needs to be public to view unless by admin
                            $sql = "SELECT AccountID, FirstName, LastName, Title FROM Employees WHERE Public=true";
                            if(!empty($additional)) $sql = "{$sql} AND ({$additional})";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>"{$row['FirstName']} {$row['LastName']}", 'aux'=>$row['Title'],'id'=>$row['AccountID']));
                                }
                            }
                            break;
                        default:
                            // sendData(); happens at end
                    }
                    // Attempt to return response
                    if (empty($tileArray)) sendError("Error Loading Tiles");
                    else sendData(array('tiles'=>$tileArray));
                    break;

                case 'bubble':
                    // TODO If $_GET['name'] or $_GET['aux'] is empty fail
                    // Set universal variables
                    $section = $_GET['section'];
                    $tileArray = array();
                    $additional = $bubbleArray = null;
                    $selObj = $_GET['id'];
                    // Get IDs if set
                    $tower = (!empty($_GET['tower']))? $_GET['tower']: null;
                    $company = (!empty($_GET['company']))? $_GET['company']: null;

                    // switch through section
                    switch ($section) {
                        case 'tower':
                            $sql = "SELECT * FROM Towers WHERE AccountID='{$selObj}'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                $row= $result->fetch_assoc();
                                $bubbleArray = array('name'=>$row['Name'],'location'=>$row['Location'],
                                    'management'=>$row['ManagementCompany'],'contact number'=>$row['ManagementContact'],
                                    'contact email'=>$row['ManagementContactEmail'], 'details'=>$row['Details'], 'id'=>$row['AccountID']);
                            }
                            break;
                        case 'company':
                            // if there's tower data use it to get company
                            if($tower) $additional = "TowerID='{$tower}'";

                            $sql = "SELECT * FROM Companies WHERE AccountID='{$selObj}'";
                            if($additional) $sql += " AND {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $bubbleArray = array('name'=>$row['Name'], 'slogan'=>$row['Slogan'], 'suites'=>$row['Suite'],
                                    'reception'=>$row['Reception'], 'phone'=>$row['ContactNumber'],
                                    'email'=>$row['ContactEmail'], 'details'=>$row['Details'], 'id'=>$row['AccountID']);
                            }
                            break;
                        case 'event':
                            // if tower/company data exist use it to get event
                            if($tower) $additional = "TowerID='{$tower}'";
                            if($company) $additional = (empty($additional))? "CompanyID='{$company}'": "{$additional} AND CompanyID='{$company}'";

                            $sql = "SELECT * FROM Events WHERE AccountID='{$selObj}'";
                            if($additional) $sql += " AND {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $bubbleArray = array('name'=>$row['Name'], 'slogan'=>$row['Slogan'], 'host'=>$row['Host'],
                                    'location'=>$row['Location'], 'details'=>$row['Details'], 'id'=>$row['AccountID']);
                            }
                            break;
                        case 'employee':
                            // if tower/company data exist use it to get employee
                            if($company) $additional = "CompanyID='{$company}'";
                            elseif ($tower) {
                                // For loop through companies adding them to additional
                                $query = "SELECT AccountID FROM Companies WHERE TowerID='{$tower}'";
                                $res = $conn->query($query);
                                if($res->num_rows > 0) {
                                    // Set additional to contain all companies
                                    while ($row = $res->fetch_assoc()) {
                                        $additional = (empty($additional))? "CompanyID='{$row['AccountID']}'": "{$additional} OR CompanyID='{$row['AccountID']}'";
                                    }
                                }
                            }

                            $sql = "SELECT * FROM Employees WHERE AccountID='{$selObj}'";
                            if($additional) $sql += " AND {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                if($row['Public']) {
                                    $bubbleArray = array('first name' => $row['FirstName'], 'last name' => $row['LastName'],
                                        'title' => $row['Title'], 'phone' => $row['Phone'], 'email' => $row['Email'], 'id'=>$row['AccountID']);
                                }
                            }
                            break;
                        default:
                    }
                    // Attempt to return response
                    if (empty($bubbleArray)) sendError("Error Loading Bubble");
                    else sendData(array('bubble'=>$bubbleArray));
                    break;

                default:
                    // Send back failed request
                    sendError("Invalid Purpose");

            }
            $conn->close();

        }
    }

    /**
     *  POST Structure
     *  cases post is called
    **/

    elseif($_SERVER["REQUEST_METHOD"] == "POST") {
        $purpose = $_POST["purpose"];
        if(!empty($purpose)) {
            // Open connection to database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            }
            $item = $_POST['item'];

            switch($purpose) {
                case 'Login':
                    // Should set the session id if successful
                    break;
                case 'Create':
                    switch ($item) {
                        case 'user':
                            // Register destination
                            break;
                        case 'tower':
                            break;
                        case 'company':
                            break;
                        case 'event':
                            break;
                        case 'employee':
                            break;
                        default:
                    }
                    break;
                case 'Update':
                    switch ($item) {
                        case 'user':
                            break;
                        case 'tower':
                            break;
                        case 'company':
                            break;
                        case 'event':
                            break;
                        case 'employee':
                            break;
                        default:
                    }
                    break;
                case 'Remove':
                    switch ($item) {
                        case 'tower':
                            break;
                        case 'company':
                            break;
                        case 'event':
                            break;
                        case 'employee':
                            break;
                        default:
                    }
                    break;
                default:
                    sendError("Invalid Purpose");
            }
            $conn->close();
        }
    }


    else sendError("Invalid Request");
?>