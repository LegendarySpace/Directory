<?php
    session_start();
    // when logged in store userID in session variable
    if (empty($_SESSION['userID'])) $_SESSION['userID'] = 'NONE';

    $servername = "sql306.epizy.com";
    $username = "epiz_25453908";
    $password = "SfKHOiolSYO3Yh";
    $dbname = "epiz_25453908_Directory";
    function sendError() {
        http_response_code(500);
        return 'Item not found';
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
		return $json;
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
        if ($obj['id'] === null) return false;
        else return true;
    }
    function getID($obj, $connect, $table) {
        if(empty($obj)||empty($table)) return false;
        // Convert aux to table column based on what table is used
        $auxArray = array('Towers'=>'Location','Companies'=>'Reception','Events'=>'Host','Employees'=>'Title','Users'=>'Password');
        $sql = "SELECT AccountID FROM {$table} WHERE Name='{$obj['name']}' AND {$auxArray[$table]}='{$obj['aux']}'";
        $result = $connect->query($sql);
        if($result->num_rows > 0) {
            // TODO Update to do deeper search, currently returns the first one it finds
            $row = $result->fetch_assoc();
            $obj['id'] = $row['id'];
            return $row['id'];
        }
        return false;
    }

    if($_SERVER["REQUEST_METHOD"] == "GET") {
        $purpose = $_GET["purpose"];
        if(!empty($purpose)) {
            // Open connection to database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            }
            
            switch ($purpose) {
                case 'splash':
                    // Set universal variables
                    $page = $_GET['page'];
                    $name = $_GET['name'];
                    $aux = $_GET['aux'];
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
                            $sql = "SELECT * FROM Towers WHERE Name='{$name}' AND Location='{$aux}'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                // Only grab the first item, assign items to $splashArray
                                $row = $result->fetch_assoc();
	                            // $admin = $_SESSION['userID'] === $row['AdminID'];
                                $splashArray = array('name'=>$name, 'location'=>$aux, 'manage'=>$row['ManagementCompany'],
                                    'phone'=>$row['ManagementContact'], 'email'=>$row['ManagementContactEmail'],
                                    'details'=>$row['Details'], 'img'=>$row['ImageURL']);
                            }
                            $sectionsArray = array('Company', 'Event', 'Employee');
                            break;
                        case 'company':
	                        $sql = "SELECT * FROM Companies WHERE Name='{$name}' AND Reception='{$aux}'";
	                        $result = $conn->query($sql);
	                        if($result->num_rows > 0) {
	                            // Only grab the first item, assign items to $splashArray
	                            $row = $result->fetch_assoc();
	                            // $admin = $_SESSION['userID'] === $row['AdminID'];
	                            $splashArray = array('name'=>$name, 'suite'=>$row['Suite'], 'reception'=>$aux,
	                                'phone'=>$row['ContactNumber'], 'email'=>$row['ContactEmail'], 'slogan'=>$row['Slogan'],
	                                'details'=>$row['Details'], 'img'=>$row['ImageURL']);
	                        }
	                        $sectionsArray = array('Event', 'Employee');
                            break;
                        default:
                            echo sendError();
                    }
                    //  if any are empty send error
                    if (empty($splashArray) || empty($sectionsArray) || $admin === null) echo sendError();
                    else echo sendData(array('splash'=>$splashArray, 'sections'=>$sectionsArray, 'admin'=>$admin));
                    break;

                case 'tiles':
                    // Set universal variables
                    $section = $_GET['section'];
                    $tileArray = array();
                    $tower = (!empty($_GET['tname']))?array('name'=>$_GET['tname'], 'aux'=>$_GET['taux'], 'id'=>null):null;
                    if(!empty($tower)) getID($tower, $conn, 'Towers');
                    $company = (!empty($_GET['cname']))?array('name'=>$_GET['cname'], 'aux'=>$_GET['caux'], 'id'=>null):null;
                    if(!empty($company)) getID($company, $conn, 'Companies');
                    $additional = null;

                    // switch through section
                    switch ($section) {
                        case 'Tower':
                            $sql = 'SELECT Name, Location FROM Towers';
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row= $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'],'aux'=>$row['Location']));
                                }
                            }
                            break;
                        case 'Company':
                            // if !empty(towerName) set $additional to 'TowerID=$towerid'
                            if(hasID($tower)) $additional = "Tower='{$tower['id']}'";

                            $sql = 'SELECT Name, Reception FROM Companies';
                            if (!empty($additional)) $sql = "{$sql} WHERE {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'], 'aux'=>['Reception']));
                                }
                            }
                            break;
                        case 'Event':
                            // if !empty(towerName) set $additional to 'TowerID=$towerid'
                            // if !empty(companyName) if !empty($additional) use $additional with %companyid, set $additional to 'CompanyID=$companyid'
                            if(hasID($tower)) $additional = "TowerID='{$tower['id']}'";
                            if(hasID($company)) $additional = (empty($additional))? "CompanyID='{$company['id']}'": "{$additional} AND CompanyID='{$company['id']}'";

                            $sql = 'SELECT Name, Host FROM Events';
                            if(!empty($additional)) $sql = "{$sql} WHERE {$additional}";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'], 'aux'=>['Host']));
                                }
                            }
                            break;
                        case 'Employee':
                            // $tower and $company are mutually exclusive
                            // if !empty(companyName) set $additional to 'CompanyID=$companyid'
                            // if !empty(towerName) set $additional to each company with 'TowerID=$towerid'
                            if (hasID($company)) $additional = "CompanyID='{$company['id']}'";
                            elseif (hasID($tower)) {
                                // For loop through companies adding them to additional
                                $query = "SELECT AccountID FROM Companies WHERE TowerID='{$tower['id']}'";
                                $res = $conn->query($query);
                                if($res->num_rows > 0) {
                                    // Set additional to contain all companies
                                    while ($row = $res->fetch_assoc()) {
                                        $additional = (empty($additional))? "CompanyID='{$row['AccountID']}'": "{$additional} OR CompanyID='{$row['AccountID']}'";
                                    }
                                }
                            }

                            // employees should be public to view unless admin
                            $sql = "SELECT Name, Title FROM Employees WHERE Public=true";
                            if(!empty($additional)) $sql = "{$sql} AND ({$additional})";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($tileArray, array('name'=>$row['Name'], 'aux'=>['Title']));
                                }
                            }
                            break;
                        default:
                            echo sendError();
                    }
                    // Attempt to return response
                    echo sendData(array('tiles'=>$tileArray));
                    break;

                case 'bubble':
                    // Set universal variables
                    $section = $_GET['section'];
                    $bubbleArray = '';
                    $selObj = array('name'=>$_GET['name'],'aux'=>$_GET['aux'], 'id'=>null);

                    // switch through section
                    switch ($section) {
                        case 'Tower':
                            break;
                        case 'Company':
                            break;
                        case 'Event':
                            break;
                        case 'Employee':
                            break;
                        default:
                    }
                    // Attempt to return response
                    echo sendData(array('bubble'=>$bubbleArray));
                    break;

                default:
                    // Send back failed request
                    echo sendError();

            }
            $conn->close();

        }
    }


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $purpose = $_POST["purpose"];
        if(!empty($purpose)) {
            // Open connection to database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            }

            $conn->close();
        }
    }

?>