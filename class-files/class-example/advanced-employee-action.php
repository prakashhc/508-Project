<?php
require_once ('connection.php');

global $conn;

function listEmployees()
{
    global $conn;
    
    $sqlQuery = "SELECT e.employee_ID as `ID`,
                        concat(e.first_name, ' ', e.last_name) as `name`,
                        e.salary as `salary`,
                        concat(m.first_name, ' ', m.last_name) as `manager`,
                        d.department_name as `department`,
                        e.email as `email`,
                        j.job_title as `job`
                 FROM employees e
                 INNER JOIN employees m ON (e.manager_ID = m.employee_ID)
                 INNER JOIN departments d ON (e.department_ID = d.department_ID)
                 INNER JOIN jobs j ON (e.job_ID = j.job_ID) ";
    
    if (! empty($_POST["search"]["value"])) {
        $sqlQuery .= 'WHERE (e.first_name LIKE "%' . $_POST["search"]["value"] . '%" OR e.last_name LIKE "%' . $_POST["search"]["value"] . '%" or j.job_title LIKE "%' . $_POST["search"]["value"] . '%") ';
    }
    
    if (! empty($_POST["order"])) {
        $sqlQuery .= 'ORDER BY ' . ($_POST['order']['0']['column'] + 1) . ' ' . $_POST['order']['0']['dir'] . ' ';
    } else {
        $sqlQuery .= 'ORDER BY e.employee_ID DESC ';
    }
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $numberRows = $stmt->rowCount();
    
    if ($_POST["length"] != - 1) {
        $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $dataTable = array();
    
    while ($sqlRow = $stmt->fetch()) {
        $dataRow = array();
        
        $dataRow[] = $sqlRow['ID'];
        $dataRow[] = $sqlRow['name'];
        $dataRow[] = $sqlRow['salary'];
        $dataRow[] = $sqlRow['manager'];
        $dataRow[] = $sqlRow['department'];
        $dataRow[] = $sqlRow['email'];
        $dataRow[] = $sqlRow['job'];
        
        $dataRow[] = '<button type="button" name="update" emp_id="' . $sqlRow["ID"] . '" class="btn btn-warning btn-sm update">Update</button>
                      <button type="button" name="delete" emp_id="' . $sqlRow["ID"] . '" class="btn btn-danger btn-sm delete" >Delete</button>';
        
        $dataTable[] = $dataRow;
    }
    
    $output = array(
        "recordsTotal" => $numberRows,
        "recordsFiltered" => $numberRows,
        "data" => $dataTable
    );
    
    echo json_encode($output);
}
    
function getEmployee()
{
    global $conn;
    
    if ($_POST["ID"]) {
        
        $sqlQuery = "SELECT employee_ID as `ID`,
                        first_name,
                        last_name,
                        salary,
                        manager_ID,
                        department_ID,
                        email,
                        job_ID
                     FROM employees
                     WHERE employee_ID = :employee_ID";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':employee_ID', $_POST["ID"]);
        $stmt->execute();
        
        echo json_encode($stmt->fetch());
    }
}

function updateEmployee()
{
    global $conn;
    
    if ($_POST['ID']) {
        
        $sqlQuery = "UPDATE employees
                        SET
                        first_name = :first_name,
                        last_name = :last_name,
                        manager_ID = :manager_ID,
                        department_ID = :department_ID,
                        email = :email,
                        job_ID = :job_ID,
                        salary = :salary
                    WHERE employee_ID = :employee_ID";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':first_name', $_POST["firstname"]);
        $stmt->bindValue(':last_name', $_POST["lastname"]);
        $stmt->bindValue(':manager_ID', $_POST["manager"]);
        $stmt->bindValue(':department_ID', $_POST["department"]);
        $stmt->bindValue(':email', $_POST["email"]);
        $stmt->bindValue(':job_ID', $_POST["job"]);
        $stmt->bindValue(':salary', $_POST["salary"]);
        $stmt->bindValue(':employee_ID', $_POST["ID"]);
        $stmt->execute();
    }
}

function addEmployee()
{
    global $conn;
    
    $sqlQuery = "INSERT INTO employees
                 (first_name, last_name, manager_ID, department_ID, email, job_ID, salary, hire_date)
                 VALUES
                 (:first_name, :last_name, :manager_ID, :department_ID, :email, :job_ID, :salary, CURDATE())";
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->bindValue(':first_name', $_POST["firstname"]);
    $stmt->bindValue(':last_name', $_POST["lastname"]);
    $stmt->bindValue(':manager_ID', $_POST["manager"]);
    $stmt->bindValue(':department_ID', $_POST["department"]);
    $stmt->bindValue(':email', $_POST["email"]);
    $stmt->bindValue(':job_ID', $_POST["job"]);
    $stmt->bindValue(':salary', $_POST["salary"]);
    $stmt->execute();
}

function deleteEmployee()
{
    global $conn;
    
    if ($_POST["ID"]) {
        
        $sqlQuery = "DELETE FROM job_history WHERE employee_ID = :employee_ID";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':employee_ID', $_POST["ID"]);
        $stmt->execute();
        
        $sqlQuery = "DELETE FROM employees WHERE employee_ID = :employee_ID";
        
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindValue(':employee_ID', $_POST["ID"]);
        $stmt->execute();
    }
}

if(!empty($_POST['action']) && $_POST['action'] == 'listEmployees') {
    listEmployees();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addEmployee') {
    addEmployee();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getEmployee') {
    getEmployee();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateEmployee') {
    updateEmployee();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteEmployee') {
    deleteEmployee();
}

?>