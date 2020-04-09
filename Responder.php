<?php
    session_start();
    // when logged in store userID in session variable

    $servername = "sql306.byetcluster.com";
    $username = "epiz_25453908";
    $password = "A80F9pKPdGx";
    $dbname = "epiz_25453908_Directory";
    function sendError() {
        http_response_code(404);
        echo 'Item not found';
    }

    if($_SERVER["REQUEST_METHOD"] == "GET") {
        $purpose = $_GET["purpose"];
        if(!empty($purpose)) {

            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            }
            
            switch ($purpose) {
                case 'landing':
                    $sqltower = "SELECT Name, Location FROM Towers";
                    $result = $conn->query($sqltower); //get towers
                    $towers = array();
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $towers = array_push($towers, array("name"=>$row["Name"],"location"=>$row["Location"]));
                        }
                    }
                    $returnValue = array("token" => NULL, "sections"=>array("Tower", "Event"), "towers"=>$towers);
                    echo json_encode($returnValue);
                    break;

                case 'tower':
                    $towername = $_GET["name"];
                    // Retrieve Tower info
                    $sqltower = "SELECT * FROM Towers WHERE Name='".$towername."'";
                    $tower = '';
                    $result = $conn->query($sqltower);
                    if($result->num_rows > 0) {
                        // Only retrieve first item
                        $row = $result->fetch_assoc();
                        $admin = $_SESSION['UserID'] == $row['AdminID'];
                        $tower = array("name"=>$row["Name"], "address"=>$row["Location"],"admin"=>true); // switch to $admin
                    } else { echo "Tower not found";}
                    
                    $returnValue = array("token" => NULL, "sections"=>array("Company", "Event", "Employee"), 'tower'=>$tower);
                    echo json_encode($returnValue);
                    break;

                case 'company':
                    $companyname = $_GET["name"];
                    // Retrieve Company info
                    $sqlcompany = $company = "";
                    $returnValue = array("token" => NULL, "sections"=>array("Event", "Employee"), "company"->$company);
                    echo json_encode($returnValue);
                    break;

                case 'splash':
                    // Set universal variables
                    $page = $_GET['page'];
                    $name = $_GET['name'];
                    $aux = $_GET['aux'];
                    $splashArray = $sectionsArray = $admin = '';
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
                            $sql = "SELECT * FROM Towers WHERE Name=\'{$name}\' AND Location=\'{$aux}\'";
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
	                        $sql = "SELECT * FROM Companies WHERE Name=\'{$name}\' AND Reception=\'{$aux}\'";
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
                            sendError();
                    }
                    echo json_encode(array('splash'=>$splashArray, 'sections'=>$sectionsArray, 'admin'=>$admin));
                    break;

                case 'tiles':
                    // switch through section
                    break;

                case 'bubble':
                    // switch through section
                    break;

                default:
                    // Send back failed request
                    sendError();

            }
            $conn->close();

        }
    }


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $purpose = $_POST["purpose"];
        if(!empty($purpose)) {

            $conn = new mysqli($servername,$username,$password,$dbname);
            if($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            }

            switch ($purpose) {
                case 'landingBtn':
                    $section = $_POST["section"];
                    if(!empty($section)) {
                        if($section == 'Tower') {
                            $sqltower = "SELECT Name, Location FROM Towers";
                            $result = $conn->query($sqltower); //get towers
                            $towers = array();
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    array_push($towers, array("name"=>$row["Name"],"aux"=>$row["Location"]));
                                }
                            }
                            echo json_encode($towers);
                        }
                        elseif($section == 'Event') {
                            $sqlevent = "SELECT Name, Host, Location FROM Events";
                            $result = $conn->query($sqlevent); // get Events
                            $events = array();
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    array_push($events, array("name"=>$row["Name"],"aux"=>$row["Host"]));
                                }
                            }
                            echo json_encode($events);
                        }
                        // End of $section switch
                    }
                break;
                case 'landingBubble':
                    $section = $_POST["section"];
                    if(!empty($section)) {
                        if($section == 'Tower') {
                            $sqltower = "SELECT * FROM Towers WHERE Name='" . $_POST["name"] . "' AND Location='" . $_POST["aux"] . "'";
                            $result = $conn->query($sqltower);
                            $row = ($result->num_rows>0) ? $result->fetch_assoc() : null;
                            $tower = '';
                            if($row != null) {
                                $tower = array("name"=>$row["Name"],"location"=>$row["Location"],"mgmtComp"=>$row["ManagementCompany"],
                                "mgmtCont"=>$row["ManagementContact"],"mgmtEmail"=>$row["ManagementContactEmail"],"details"=>$row["Details"]);
                            } else { echo "Data Failed to Load"; }
                            echo json_encode($tower);
                        }
                        elseif($section == 'Event') {
                            $sqlevent = "SELECT * FROM Events WHERE Name='" . $_POST["name"] . "' AND Host='" . $_POST["aux"] . "'";
                            $result = $conn->query($sqlevent);
                            $row = ($result->num_rows>0) ? $result=>fetch_assoc() : null;
                            $event = '';
                            if($row != null) {
                                $event = array("name"=>$row["Name"],"host"=>$row["Host"],"slogan"=>$row["Slogan"],"location"=>$row["Location"],"details"=>$row["Details"],);
                            } else {echo "Event Failed to Load";}
                            echo json_encode($event);
                        }
                        // End of $section switch
                    }
                break;
                case 'towerBtn':
                    $section = $_POST["section"];
                    $result = $conn->query("SELECT AccountID FROM Towers WHERE Name=".$_POST["name"]);
                    $towerID = ($result->num_rows > 0)? $result->fetch_assoc()['AccountID'] : null;
                    if(!empty($section)) {
                        if($section == 'Company') {
                            //
                            $sqlcompany = "SELECT * FROM Companies WHERE TowerID='".$towerID"'";
                            $result = $conn->query($sqlcompany);
                            $companies = array();
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    array_push($companies, array(""=>$row[""]));
                                }
                            }
                        }
                        elseif ($section == "Event") {
                            //
                            $sqlevent = "SELECT * FROM Events WHERE TowerID='".$towerID"'";
                            $result = $conn->query($sqlevent):
                            $events = array();
                        }
                        elseif ($section == "Employee") {
                            //
                            $sqlcompany = "SELECT * FROM Companies WHERE TowerID='".$towerID"'";
                            $result = $companyID = '';

                            // for each companyID
                            $sqlemployee = "SELECT * FROM Employees WHERE CompanyID='".$companyID"' AND Public=true";
                            $result = $conn->query($sqlemployee);
                            $employees = array();
                        }
                        // End of $section switch
                    }
                break;
                case 'companyBtn':
                    //s
                break;
            }

            $conn->close();
        }
    }

?>