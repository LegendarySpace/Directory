<?php
    session_start();
    // when logged in store userID in session variable
    if (empty($_SESSION['userID'])) $_SESSION['userID'] = 'NONE';

    $servername = "sql306.epizy.com";
    $username = "epiz_25453908";
    $password = "SfKHOiolSYO3Yh";
    $dbname = "epiz_25453908_Directory";
    function sendError($str) {
        http_response_code(404);
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
                            // TODO simpler if I separate tower and company from registration
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
                    else sendData(array('splash'=>$splashArray, 'sections'=>$sectionsArray, 'admin'=>$admin));
                    break;

                case 'tiles':
                    // Set universal variables
                    $section = $_GET['section'];
                    $tileArray = array();
                    $additional = null;
                    // Get IDs if set, can be null
                    $tower = (!empty($_GET['tower']))? $_GET['tower']: null;
                    $company = (!empty($_GET['company']))? $_GET['company']: null;

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
                            if($tower) $additional = "TowerID='{$tower}'";

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
                            // TODO if tower retrieve all events associated with the tower or any company in the tower.
                            // handle filtering on client side.
                            if($tower) $additional = "TowerID='{$tower}' AND";
                            if($company) $additional = (empty($additional))? "CompanyID='{$company}'": "{$additional} CompanyID='{$company}'";
                            // TODO Get all companies in the tower and use the to check for events
                            if ($tower) {
                                // For loop through companies adding them to additional
                                $query = "SELECT AccountID FROM Companies WHERE TowerID='{$tower}'";
                                $res = $conn->query($query);
                                if($res->num_rows > 0) {
                                    // Set additional to contain all companies
                                    while ($row = $res->fetch_assoc()) {
                                        $additional = "{$additional} OR CompanyID='{$row['AccountID']}'";
                                    }
                                }
                            }

                            $sql = "SELECT AccountID, Name, Host FROM Events";
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
                            // Either set the specific company or all possible companies
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
                            // TODO Need a way to define security to have access to all employees but limit their data
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
                            if($additional) $sql .= " AND {$additional}";
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
                            if($additional) $sql .= " AND {$additional}";
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
                            if($additional) $sql .= " AND {$additional}";
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
                case 'login':
                    // Should set the session id if successful
                    $user = $_POST['username'];
                    $pass = $_POST['password'];
                    $sql = "SELECT * FROM Users WHERE Username={$user} AND Password={$pass}";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $_SESSION['userID'] = $row['AccountID'];
                        // TODO Potentially add icon
                        sendData(array('name'=>"{$row['FirstName']} {$row['LastName']}"));
                    } else sendError("Username or Password incorrect");
                    break;
                case 'create':
                    switch ($item) {
                        case 'user':
                            // Register destination
                            $user = $_POST["username"];
                            $pass = $_POST["password"];
                            $first = $_POST["first name"];
                            $last = $_POST['last name'];
                            $userID = generateID();
                            $sql = "INSERT INTO `Users`(`Username`, `AccountID`, `Password`, `FirstName`, `LastName`, `ImgURL`)
                                VALUES ({$user},{$userID},{$pass},{$first},{$last},null)";
                            // Unique action when successful, die to prevent resubmission
                            if($conn->query($sql) === TRUE) {
                                sendData(array('name'=>"{$first} {$last}", 'id'=>$userID));
                            } else {
                                sendError("Error registering user: " . $conn->error);
                            }
                            die();
                            break;
                        case 'tower':
                            $name = $_POST["name"];
                            $userID = generateID();
                            $adminID = $_POST['id'];
                            $loc = $_POST['location'];
                            $mgmt = $_POST["management company"];
                            $phone = $_POST['number'];
                            $email = $_POST['email'];
                            $img = null;
                            $details = $_POST['details'];
                            $sql = "INSERT INTO `Towers`(`Name`, `AccountID`, `AdminID`, `Location`, `ManagementCompany`,
                                `ManagementContact`, `ManagementContactEmail`, `ImageURL`, `Details`)
                                VALUES ({$name},{$userID},{$adminID},{$loc},{$mgmt},{$phone},{$email},{$img},{$details})";
                            break;
                        case 'company':
                            $name = $_POST["name"];
                            $userID = generateID();
                            $adminID = $_POST['id'];
                            $tower = null; // TODO find way to connect to tower
                            $suites = implode(", ", $_POST['suites']);
                            $reception = $_POST['reception'];
                            $phone = $_POST['number'];
                            $email = $_POST['email'];
                            $slogan = $_POST['slogan'];
                            $img = $_POST['image'];
                            $details = $_POST['details'];
                            $sql = "INSERT INTO `Companies`(`Name`, `AccountID`, `AdminID`, `TowerID`, `Suite`, `Reception`,
                                `ContactNumber`, `ContactEmail`, `Slogan`, `Details`, `ImageURL`) 
                                VALUES ({$name},{$userID},{$adminID},{$tower},{$suites},{$reception},{$phone},{$email},{$slogan},{$img},{$details})";
                            break;
                        case 'event':
                            $name = $_POST["name"];
                            $userID = generateID();
                            $company = $_POST['company'];
                            $tower = $_POST['tower'];
                            $host = $_POST['host'];
                            $slogan = $_POST['slogan'];
                            $loc = $_POST['location'];
                            $details = $_POST['details'];
                            $sql = "INSERT INTO `Events`(`Name`, `AccountID`, `CompanyID`, `TowerID`, `Host`, `Slogan`, `Location`, `Details`)
                                VALUES ({$name},{$userID},{$company},{$tower},{$host},{$slogan},{$loc},{$details})";
                            break;
                        case 'employee':
                            $first = $_POST["first name"];
                            $last = $_POST["last name"];
                            $userID = generateID();
                            $company = null; // TODO find way to connect company
                            $title = $_POST['title'];
                            $phone = $_POST['phone'];
                            $email = $_POST['email'];
                            $address = $_POST['address'];
                            $pub = $_POST['public'];
                            $img = $_POST['image'];
                            $sql = "INSERT INTO `Employees`(`FirstName`, `LastName`, `AccountID`, `CompanyID`, `Title`, `Phone`, 
                                `Email`, `Address`, `Public`, `ImageURL`) 
                                VALUES ({$first},{$last},{$userID},{$company},{$title},{$phone},{$email},{$address},{$pub},{$img})";;
                            break;
                        default:
                            sendError("No Item specified to create");
                    }
                    if($conn->query($sql) === TRUE) {
                        echo "item created successfully";
                    } else {
                        sendError("Error registering item: " . $conn->error);
                    }
                    break;
                case 'update':
                    $userID = $_POST['id'];
                    $type = $_POST['type'];
                    $value = $_POST['value'];
                    if(empty($userID) || empty($type)) sendError("Invalid values");
                    switch ($item) {
                        case 'user':
                            // TODO convert generalized type to column name
                            $sql = "UPDATE `Users` SET `{$type}`={$value} WHERE AccountID={$userID}";
                            break;
                        case 'tower':
                            // TODO convert generalized type to column name
                            $sql = "UPDATE `Towers` SET `{$type}`={$value} WHERE AccountID={$userID}";
                            break;
                        case 'company':
                            // TODO convert generalized type to column name
                            $sql = "UPDATE `Companies` SET `{$type}`={$value} WHERE AccountID={$userID}";
                            break;
                        case 'event':
                            // TODO convert generalized type to column name
                            $sql = "UPDATE `Events` SET `{$type}`={$value} WHERE AccountID={$userID}";
                            break;
                        case 'employee':
                            // TODO convert generalized type to column name
                            $sql = "UPDATE `Employees` SET `{$type}`={$value} WHERE AccountID={$userID}";
                            break;
                        default:
                            sendError("No Item specified for update");
                    }
                    if($conn->query($sql) === TRUE) {
                        echo "item updated successfully";
                    } else {
                        sendError("Error updating item: " . $conn->error);
                    }
                    break;
                case 'remove':
                    $userID = $_POST['id'];
                    switch ($item) {
                        case 'tower':
                            $sql = "DELETE FROM `Towers` WHERE AccountID={$userID}";
                            break;
                        case 'company':
                            $sql = "DELETE FROM `Companies` WHERE AccountID={$userID}";
                            break;
                        case 'event':
                            $sql = "DELETE FROM `Events` WHERE AccountID={$userID}";
                            break;
                        case 'employee':
                            $sql = "DELETE FROM `Employees` WHERE AccountID={$userID}";
                            break;
                        default:
                            sendError("Invalid Item");
                    }
                    if($conn->query($sql) === TRUE) {
                        echo "Item successfully removed";
                    } else {
                        sendError("Error removing item: " . $conn->error);
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