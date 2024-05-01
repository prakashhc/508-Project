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
                        d.department_name as `department`
                 FROM employees e
                 INNER JOIN employees m ON (e.manager_ID = m.employee_ID)
                 INNER JOIN departments d ON (e.department_ID = d.department_ID)";
    
    $stmt = $conn->prepare($sqlQuery);
    $stmt->execute();
    
    $numberRows = $stmt->rowCount();
    
    $dataTable = array();
    
    while ($sqlRow = $stmt->fetch()) {
        $dataRow = array();
        
        $dataRow[] = $sqlRow['ID'];
        $dataRow[] = $sqlRow['name'];
        $dataRow[] = $sqlRow['salary'];
        $dataRow[] = $sqlRow['manager'];
        $dataRow[] = $sqlRow['department'];
        
        $dataTable[] = $dataRow;
    }
    
    $output = array(
        "recordsTotal" => $numberRows,
        "recordsFiltered" => $numberRows,
        "data" => $dataTable
    );
    
    echo json_encode($output);
}


if(!empty($_POST['action']) && $_POST['action'] == 'listEmployees') {
    listEmployees();
}

?>