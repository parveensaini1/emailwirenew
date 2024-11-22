<?php

/**
 * Settings Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class SettingsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Settings';

    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Setting');

    /**
     * Helpers used by the Controller
     *
     * @var array
     * @access public
     */
    public $helpers = array('Html', 'Form');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'settings');
        $this->set('model', 'Setting');
        $this->set('menutitle', 'Settings');
        $this->set('menutitle_add', 'Setting'); 
    }

    public function index() {
        $this->set('title_for_layout', __('All Settings'));

        $this->Setting->recursive = 0;
        $this->paginate = array("order" => "Setting.weight ASC");

        // if (isset($this->request->params['named']['p'])) {
        //     // $this->paginate = array("Setting.key LIKE '" . $this->request->params['named']['p'] . "%'"));
        // }
        $this->set('settings', $this->paginate());
    }

    public function view($id = null) {
        if (!$id) {
            $id=base64_decode($id);
            $this->Session->setFlash(__('Invalid Setting.'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('setting', $this->Setting->read(null, $id));
    }

    public function add() {
        $this->set('title_for_layout', __('Add Setting'));

        if (!empty($this->request->data)) {
            $this->Setting->create();
            if ($this->Setting->save($this->request->data)) {
                $this->Session->setFlash(__('The setting has been saved'), 'success', array('class' => 'success'));
                if(!empty($this->data["Setting"]["redirect"])){
                    $this->redirect(array("controller"=>$this->data["Setting"]["redirect"],'action' => 'index'));
                }else{
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('The Setting could not be saved. Please, try again.'), 'error', array('class' => 'error'));
            }
        }
    }

    public function edit($id = null) {
        $this->set('title_for_layout', __('Edit Setting'));

        if (!$id) { 
            $this->Session->setFlash(__('Invalid Setting'), 'error', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        $id=base64_decode($id);
        if (!empty($this->request->data)) {
            if ($this->Setting->save($this->request->data)) {
                $this->Session->setFlash(__('The Setting has been saved'), 'success');
                if(!empty($this->data["Setting"]["redirect"])){
                    $this->redirect(array("controller"=>$this->data["Setting"]["redirect"],'action' => 'index'));
                }else{
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('The setting could not be saved. Please, try again.'),'error');
            }
        }else{
            $this->request->data = $this->Setting->read(null, $id);
        }
    }

    public function delete($id = null) {
        if (!$id) { 
            $this->Session->setFlash(__('Invalid id for Setting'), 'error', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        $id=base64_decode($id);
        if ($this->Setting->delete($id)) {
            $this->Session->setFlash(__('Setting deleted'), 'success', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function prefix($prefix = null) {
        $this->set('title_for_layout', sprintf(__('Settings: %s'), $prefix));

        if (!empty($this->request->data) && $this->Setting->saveAll($this->request->data['Setting'])) {
            $this->Session->setFlash(__("Settings updated successfully"), 'success', array('class' => 'success'));
        }

        $settings = $this->Setting->find('all', array(
            'order' => 'Setting.weight ASC',
            'conditions' => array(
                'Setting.key LIKE' => $prefix . '.%',
                'Setting.editable' => 1,
            ),
        ));
        //'conditions' => "Setting.key LIKE '".$prefix."%'"));
        $this->set(compact('settings'));

        if (count($settings) == 0) {
            $this->Session->setFlash(__("Invalid Setting key"), 'error', array('class' => 'error'));
        }

        $this->set("prefix", $prefix);
    }

    public function moveup($id, $step = 1) {
         $id=base64_decode($id);
        if ($this->Setting->moveup($id, $step)) {
            $this->Session->setFlash(__('Moved up successfully'), 'success', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__('Could not move up'), 'error', array('class' => 'error'));
        }

        $this->redirect(array('controller' => 'settings', 'action' => 'index'));
    }

    public function movedown($id, $step = 1) {
         $id=base64_decode($id);
        if ($this->Setting->movedown($id, $step)) {
            $this->Session->setFlash(__('Moved down successfully'), 'success', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__('Could not move down'), 'error', array('class' => 'error'));
        }

        $this->redirect(array('controller' => 'settings', 'action' => 'index'));
    }

    /** Download table from server */
    public function getrecorddump($table=null){
        if($this->Auth->user('user_role') == 1 && $this->Auth->user('id') != 1){
            $this->Session->setFlash(__('Wrong access.',true), 'error');
            $this->redirect(array('controller' =>'settings','action'=>'index'));
        }
        $dbConfig  = ConnectionManager::getDataSource('default')->config;
        // pr($dbConfig);die;
        // Get connection object and set the charset
        $conn = mysqli_connect($dbConfig['host'],$dbConfig['login'],$dbConfig['password'],$dbConfig['database']);
        $conn->set_charset("utf8");


        // Get All Table Names From the Database
        //$tables = array();
        /*$sql = "SHOW TABLES";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        $tables[] = 'supplementaries';
        $tables[] = 'exam_subjects';
        $tables[] = 'temp_exam_subjects';*/
        //foreach ($tables as $table) {
            
            // Prepare SQLscript for creating table structure
        $sqlScript = "";
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);            
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";
        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);
        
        $columnCount = mysqli_num_fields($result);
        
        // Prepare SQLscript for dumping data for each table
        for ($i = 0; $i < $columnCount; $i ++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j ++) {
                    $row[$j] = $row[$j];
                    
                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        
        $sqlScript .= "\n"; 
        //}

        if(!empty($sqlScript)){
            // Save the SQL script to a backup file
            $backup_file_name = $table . '_backup_' . time() . '.sql';
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler); 

            // Download the SQL backup file to the browser
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup_file_name));
            ob_clean();
            flush();
            readfile($backup_file_name);
            exec('rm ' . $backup_file_name); 
        }
        die;
    }

    public function dataviewer(){
        if($this->Auth->user('staff_role_id') != 1){
            $this->Session->setFlash(__('Wrong access.',true), 'success');
            $this->redirect(array('controller' => 'staffUsers' ,'action'=>'dashboard'));
        }
        if($this->request->is('post')){ 
            $flashMsg = '';
             
            if(isset($this->data["Setting"]) && isset($this->data['Setting']['script'])){
                echo '<pre>';               
                $sql = trim($this->data['Setting']['script']);
                echo 'Your Entered Query : <br>';
                echo $sql;
                echo '<br>';
                echo "<h2><a href='" . SITEURL . "settings/dataviewer". "' >Try Again New</a></h2>";
                echo '<br>';
                echo '<br>';
                 
                $conn = new mysqli(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD,DB_DATABASE);
                 
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }  
                
                $result = $conn->query($sql); 
                // if(isset($result)  && !empty($result) && $result == 1){
                    // echo 'Done';
                // }else if(isset($result)  && !empty($result) && $result == 0){
                    // echo 'Not Done';
                // }else 
                    
                if(isset($result) && !empty($result)){
                    $row_cnt = $result->num_rows;
     
                    echo '<br> Total Records Count : ';
                    echo $row_cnt;
                    echo '<br>';                    
                
                    if ($result->num_rows > 0) {
                        echo "<table border='1'>";                   
                        $counter = 0;
                        $fields = array();
                        while($row = $result->fetch_array()) {
                            
                            $counterMax = count($row);
                            $counterMaxFinal = $counterMax/2;
                            
                            if($counter == 0){
                                echo "<tr>";
                                $tempRow = array_keys($row);
                                $tempRowCount = count(array_keys($row));
                                $tempCounter = 0;
                                $rCount = 0;
                                for($r = 0; $r < $tempRowCount; $r++){
                                    if($tempCounter%2 != 0){
                                        $fields[] = $tempRow[$r];
                                    } 
                                    $tempCounter++;
                                    $rCount++;
                                }
                                // print_r($tempRow); 
                                $fieldsCounter = count($fields);
                                foreach($fields as $keyTemp => $valueTemp){
                                    echo "<th>";
                                    if(isset($valueTemp)){
                                        echo $valueTemp;
                                    }
                                    echo "</th>";
                                }
                                echo "</tr>";
                            }
                            
                            echo "<tr>";
                             
                            for($r = 0; $r < $counterMaxFinal; $r++){
                                echo "<td>";
                                if(isset($row[$r])){
                                    echo $row[$r];
                                }
                                echo "</td>";
                            } 
                            // print_r($row);
                            $counter++;
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "0 results";
                    }
                }else{
                    
                    echo "Unable to execute query";
                    echo '<br>';
                    echo "<h2><a href='" . SITEURL . "settings/dataviewer". "' >Please Click Here To Try Again New</a></h2>";
                    echo '<br>';
                }               
                $conn->close(); 
                die;
            }
        } 
    }

}
