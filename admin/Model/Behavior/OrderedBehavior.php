<?php

/**
 * OrderedBehavior
 *
 * @developer Alexander Morland ( aka. alkemann)
 * @license MIT
 * @version 2.1
 * @modified 27. august 2008
 *
 * This behavior lets you order items in a very similar way to the tree
 * behavior, only there is only 1 level. You can however have many 
 * independent lists in one table. Usually you use a foreign key to
 * set / see what list you are in (see example bellow) or if you have
 * just one list for the entire table, you can do that too.
 * 
 * What it does:
 * 
 * It manages the creation and updating of the order field. It 
 * also sets the models order property to this field. When adding new
 * nodes or deleting old ones, this behavior will do the necisary changes
 * to keep the list working properly. It is build to be completely
 * automagic after the initial configuration by letting it know 
 * your foreign_key and weight fields.
 * 
 * Usage example :
 * 
 * Lets say you have books with pages and want the pages ordered
 * by page number (obviously a book sorted alphabetically would be 
 * silly). So you have these models:
 * 
 * Book hasMany Page
 * Page belongsTo Book
 * 
 * The Page model has fields : 
 * 
 * id
 * content
 * book_id 
 * page_number
 * 
 * To set up this behavior we add this property to the Page model :
 * 
 * var $actsAs = array('Ordered' => array(
 * 			'field' 		=> 'page_number',
 * 			'foreign_key' 	=> 'book_id'
 * 		));
 * 
 * Now when you save a new page (no changes needed to action or view,
 * but leave page_number out of the form), it will be added to the end 
 * of the book.
 * 
 * When deleting, the weights will automatically be adjusted to fill in
 * the vacum. 
 * 
 * NB! Note that if using Model::deleteAll() it is VERY important that you
 * assign it to use callbacks 'beforeDelete' and 'afterDelete', like this:
 * 
 * // in controller action
 * $this->Page->deleteAll(array('user_id'=>22),true,array('beforeDelete','afterDelete'));
 * 
 * Now lets say the last two pages to be created got made in the wrong 
 * order, so you want to move the last page "up" one space. With the 
 * a simple controller call to the model like this that can be achieved:
 * 
 * // in a controller action :
 * $this->Page->moveup($id);
 * // the id here is the id of the newest page
 * 
 * You find that the first page you made is suppose to be the 5 pages later:
 * 
 * // in a controller action :
 * $this->Page->movedown($id, 5);
 * 
 * Also you discovered that in the first page got put in the middle. This 
 * can easily be moved first by doing this :
 * 
 * // in a controller action :
 * $this->Page->moveup($id,true);
 * // true will move it to the extre in that direction
 * 
 * You can also use actions to find out if the node is first or last page :
 * 
 *  - isfirst($id)
 *  - islast($id)
 *  
 * And a last feature is the ability to sort the list by any field
 * you want and have it set weights based on that. You do that like this :
 * 
 * //in controller action :
 * $this->Page->sortby('content DESC', $book_id);
 * // dont ask me why you would sort the pages of a book by its content lol
 *  
 * Note that this behaviour will also let you sort an entire table as one list.
 * To do that you simply set the 'foreign_key' to false (and dont create the field
 * in the table). Now there will only be one set of weights. (Note you need the weight
 * field as normal)
 * 
 * @author Alexander Morland aka alkemann
 * @license MIT
 * @modified 17. nov. 2008 (model independent settings)
 * @version 2.1.3
 * 
 */
class OrderedBehavior extends ModelBehavior {

    public $name = 'Ordered';

    /**
     * field : (string) The field to be ordered by. 
     * 
     * foreign_key : (string) The field to identify one SET by. 
     * 				 Each set has their own order (ie they start at 1).
     *               Set to FALSE to not use this feature (and use only 1 set)
     */
    public $_defaults = array('field' => 'weight', 'foreign_key' => 'order_id');

    public function setup(Model $model, $config = array()) {
        parent::setup($model, $config);
        if (!is_array($config)) {
            $config = array();
        }
        $this->settings[$model->alias] = array_merge($this->_defaults, $config);
        $model->order = $model->alias . '.' . $this->settings[$model->alias]['field'] . ' ASC';
    }

    public function beforeDelete(Model $model, $cascade = true) {
        parent::beforeDelete($model, $cascade);
        $model->read();
        $highest = $this->_highest($model);
        if (!empty($model->data) && ($model->data[$model->alias][$model->primaryKey] == $highest[$model->alias][$model->primaryKey])) {
            $model->data = null;
        }
        return true;
    }

    public function afterDelete(Model $model) {
        parent::afterDelete($model);
        if ($model->data) {
            // What was the weight of the deleted model?		
            $old_weight = $model->data[$model->alias][$this->settings[$model->alias]['field']];
            // update the weight of all models of higher weight by


            $action = array($this->settings[$model->alias]['field'] => $this->settings[$model->alias]['field'] . ' - 1');
            $conditions = array(
                $model->alias . '.' . $this->settings[$model->alias]['field'] . ' >' => $old_weight);
            if ($this->settings[$model->alias]['foreign_key']) {
                $conditions[$model->alias . '.' . $this->settings[$model->alias]['foreign_key']] = $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']];
            }
            // decreasing them by 1
            return $model->updateAll($action, $conditions);
        }
        return true;
    }

    /**
     * Sets the weight for new items so they end up at end
     *
     * @todo add new model with weight. clean up after
     * @param Model $model
     */
    public function beforeSave(Model $model, $options = array()) {
        parent::beforeSave($model, $options);
        //	Check if weight id is set. If not add to end, if set update all
        // rows from ID and up
        if (!isset($model->data[$model->alias][$model->primaryKey])) {
            // get highest current row
            $highest = $this->_highest($model);
            // set new weight to model as last by using current highest one + 1
            $model->data[$model->alias][$this->settings[$model->alias]['field']] = $highest[$model->alias][$this->settings[$model->alias]['field']] + 1;
        }
        return true;
    }

    /**
     * Moving a node to specific weight, it will shift the rest of the table to make room.
     *
     * @param Object $model
     * @param int $id The id of the node to move
     * @param int $new_weight the new weight of the node
     * @return boolean True of move successful
     */
    public function moveto(&$model, $id = null, $new_weight = null) {
        if (!$id || !$new_weight || $new_weight < 1) {
            return false;
        }
        $highest = $this->_highest($model);
        // fetch the model and its old weight
        $old_weight = $this->_read($model, $id);

        //check if new weight is too big
        if ($new_weight > $highest[$model->alias][$this->settings[$model->alias]['field']]) {
            return false;
        }
        if ($new_weight === true && $old_weight == 0) {
            $new_weight = $highest[$model->alias][$this->settings[$model->alias]['field']] + 1;
        }
        if (empty($model->data)) {
            return false;
        }
        $conditions = array();
        if ($this->settings[$model->alias]['foreign_key']) {
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['foreign_key']] = $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']];
        }

        // give Model new weight	
        $model->data[$model->alias][$this->settings[$model->alias]['field']] = $new_weight;
        if ($new_weight == $old_weight) {
            // move to same location?
            return false;
        } elseif ($old_weight == 0) {
            $action = array(
                $model->alias . '.' . $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' + 1');
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' >='] = $new_weight;
        } elseif ($new_weight > $old_weight) {
            // move all nodes that have weight > old_weight AND <= new_weight up one (-1)
            $action = array(
                $model->alias . '.' . $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' - 1');
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' <='] = $new_weight;
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' >'] = $old_weight;
        } else { // $new_weight < $old_weight
            // move all where weight >= new_weight AND < old_weight down one (+1)	
            $action = array(
                $model->alias . '.' . $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' + 1');
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' >='] = $new_weight;
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' <'] = $old_weight;
        }
        $model->updateAll($action, $conditions);
        return $model->save(null, false);
    }

    /**
     * Take in an order array and sorts the list based on that order specification
     * and creates new weights for it. If no foreign key is supplied, all lists
     * will be sorted.
     *
     * @todo foreign key independent
     * @param Object $model
     * @param array $order
     * @param mixed $foreign_key
     * $returns boolean true if successfull
     */
    public function sortby(&$model, $order, $foreign_key = null) {
        $fields = array($model->primaryKey, $this->settings[$model->alias]['field']);
        $conditions = array(1 => 1);
        if ($this->settings[$model->alias]['foreign_key']) {
            if (!$foreign_key) {
                return false;
            }
            $fields[] = $this->settings[$model->alias]['foreign_key'];
            $conditions = array(
                $model->alias . '.' . $this->settings[$model->alias]['foreign_key'] => $foreign_key);
        }

        $all = $model->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions,
            'recursive' => -1,
            'order' => $order));
        $i = 1;
        foreach ($all as $key => $one) {
            $all[$key][$model->alias][$this->settings[$model->alias]['field']] = $i++;
        }
        return $model->saveAll($all);
    }

    /**
     * Reorder the node, by moving it $number spaces up. Defaults to 1
     *
     * If the node is the first node (or less then $number spaces from first)
     * this method will return false.
     * 
     * @param AppModel $model
     * @param mixed $id The ID of the record to move
     * @param mixed $number how many places to move the node or true to move to last position
     * @return boolean true on success, false on failure
     * @access public
     */
    public function moveup(&$model, $id = null, $number = 1) {
        if (!$id) {
            if ($model->id) {
                $id = $model->id;
            } elseif (!empty($model->data) && isset($model->data[$model->alias][$model->primaryKey])) {
                $id = $model->data[$model->alias][$model->primaryKey];
            } else {
                return false;
            }
        }
        $old_weight = $this->_read($model, $id);
        if (empty($model->data)) {
            return false;
        }
        if (is_numeric($number)) {
            if ($number == 1) { // move 1 space
                $previous = $this->_previous($model);
                if (!$previous) {
                    return false;
                }
                $model->data[$model->alias][$this->settings[$model->alias]['field']] = $previous[$model->alias][$this->settings[$model->alias]['field']];

                $previous[$model->alias][$this->settings[$model->alias]['field']] = $old_weight;

                $data[0] = $model->data;
                $data[1] = $previous;

                return $model->saveAll($data, array('validate' => false));
            } elseif ($number < 1) { // cant move 0 or negative spaces
                return false;
            } else { // move Model up N spaces UP
                if ($this->settings[$model->alias]['foreign_key']) {
                    $conditions = array(
                        $model->alias . '.' . $this->settings[$model->alias]['foreign_key'] => $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']]);
                } else {
                    $conditions = array();
                }

                // find the one occupying new space and its weight
                $new_weight = $model->data[$model->alias][$this->settings[$model->alias]['field']] - $number;
                // check if new weight is possible. else move last
                if (!$this->_findByWeight($model, $new_weight)) {
                    return false;
                }
                $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' >='] = $new_weight;
                $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' <'] = $old_weight;
                // increase weight of all where weight > new weight and id != Model.id		
                $model->updateAll(array(
                    $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' + 1'), $conditions);

                // set Model weight to new weight and save it
                $model->data[$model->alias][$this->settings[$model->alias]['field']] = $new_weight;
                return $model->save(null, false);
            }
        } elseif (is_bool($number) && $number && $model->data[$model->alias][$this->settings[$model->alias]['field']] != 1) { // move Model FIRST;
            if ($this->settings[$model->alias]['foreign_key']) {
                $conditions = array(
                    $model->alias . '.' . $this->settings[$model->alias]['field'] . ' <' => $old_weight,
                    $model->alias . '.' . $this->settings[$model->alias]['foreign_key'] => $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']]);
            } else {
                $conditions = array(
                    $model->alias . '.' . $this->settings[$model->alias]['field'] . ' <' => $old_weight);
            }
            $model->id = $model->data[$model->alias][$model->primaryKey];
            $model->saveField($this->settings[$model->alias]['field'], 0);
            $model->updateAll(array(// update
                $model->alias . '.' . $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' + 1'), $conditions);

            return true;
        } else { // $number is neither a number nor a bool
            return false;
        }
    }

    /**
     * This will create weights based on display field. The purpose of the method is to create
     * weights for tables that existed before this behavior was added.
     *
     * @param Object $model
     * @return boolean success
     */
    public function resetweights(&$model) {
        if ($this->settings[$model->alias]['foreign_key']) {
            $temp = $model->find('all', array(
                'fields' => $this->settings[$model->alias]['foreign_key'],
                'group' => $this->settings[$model->alias]['foreign_key'],
                'recursive' => -1));
            $foreign_keys = Set::extract($temp, '{n}.' . $model->alias . '.' . $this->settings[$model->alias]['foreign_key']);
            foreach ($foreign_keys as $fk) {
                $all = $model->find('all', array(
                    'conditions' => array($this->settings[$model->alias]['foreign_key'] => $fk),
                    'fields' => array(
                        $model->displayField,
                        $model->primaryKey,
                        $this->settings[$model->alias]['field'],
                        $this->settings[$model->alias]['foreign_key']),
                    'order' => $model->displayField));
                $i = 1;
                foreach ($all as $key => $one) {
                    $all[$key][$model->alias][$this->settings[$model->alias]['field']] = $i++;
                }
                if (!$model->saveAll($all)) {
                    return false;
                }
            }
        } else {
            $all = $model->find('all', array(
                'fields' => array(
                    $model->displayField,
                    $model->primaryKey,
                    $this->settings[$model->alias]['field']),
                'order' => $model->displayField));
            $i = 1;
            foreach ($all as $key => $one) {
                $all[$key][$model->alias][$this->settings[$model->alias]['field']] = $i++;
            }
            if (!$model->saveAll($all)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Reorder the node, by moving it $number spaces down. Defaults to 1
     *
     * If the node is the last node (or less then $number spaces from last)
     * this method will return false.
     *
     * @param AppModel $model
     * @param mixed $id The ID of the record to move
     * @param mixed $number how many places to move the node or true to move to last position
     * @return boolean true on success, false on failure
     * @access public
     */
    public function movedown(&$model, $id = null, $number = 1) {
        if (!$id) {
            if ($model->id) {
                $id = $model->id;
            } elseif (!empty($model->data) && isset($model->data[$model->alias][$model->primaryKey])) {
                $id = $model->data[$model->alias][$model->primaryKey];
            } else {
                return false;
            }
        }
        $old_weight = $this->_read($model, $id);
        if (empty($model->data)) {
            return false;
        }
        if (is_numeric($number)) {
            if ($number == 1) { // move node 1 space down
                $next = $this->_next($model);
                if (!$next) { // it is the last node
                    return false;
                }
                // switch the node's weight around		
                $model->data[$model->alias][$this->settings[$model->alias]['field']] = $next[$model->alias][$this->settings[$model->alias]['field']];

                $next[$model->alias][$this->settings[$model->alias]['field']] = $old_weight;

                // create an array of the two nodes and save them
                $data[0] = $model->data;
                $data[1] = $next;
                return $model->saveAll($data, array('validate' => false));
            } elseif ($number < 1) { // cant move 0 or negative number of spaces
                return false;
            } else { // move Model up N spaces DWN
                if ($this->settings[$model->alias]['foreign_key']) {
                    $conditions = array(
                        $model->alias . '.' . $this->settings[$model->alias]['foreign_key'] => $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']]);
                } else {
                    $conditions = array();
                }

                // find the one occupying new space and its weight
                $new_weight = $model->data[$model->alias][$this->settings[$model->alias]['field']] + $number;
                // check if new weight is possible. else move last
                if (!$this->_findByWeight($model, $new_weight)) {
                    return false;
                }
                // increase weight of all where weight > new weight and id != Model.id				


                $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' <='] = $new_weight;
                $conditions[$model->alias . '.' . $this->settings[$model->alias]['field'] . ' >'] = $old_weight;

                $model->updateAll(array(
                    $this->settings[$model->alias]['field'] => $this->settings[$model->alias]['field'] . ' - 1'), $conditions);

                // set Model weight to new weight and save it
                $model->data[$model->alias][$this->settings[$model->alias]['field']] = $new_weight;
                return $model->save(null, false);
            }
        } elseif (is_bool($number) && $number) { // move Model LAST;
            if ($this->settings[$model->alias]['foreign_key']) {
                $conditions = array(
                    $model->alias . '.' . $this->settings[$model->alias]['field'] . ' >' => $old_weight,
                    $model->alias . '.' . $this->settings[$model->alias]['foreign_key'] => $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']]);
            } else {
                $conditions = array(
                    $model->alias . '.' . $this->settings[$model->alias]['field'] . ' >' => $old_weight);
            }

            // get highest weighted row
            $highest = $this->_highest($model);
            // check of Model is allready highest
            if ($highest[$model->alias][$model->primaryKey] == $model->data[$model->alias][$model->primaryKey]) {
                return false;
            }
            // Save models as highest +1
            $model->saveField($this->settings[$model->alias]['field'], $highest[$model->alias][$this->settings[$model->alias]['field']] + 1);
            // updated all by taking off 1
            $model->updateAll(array(// action 
                $model->alias . '.' . $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' - 1'), $conditions);

            return true;
        } else { // $number is neither a number nor a bool
            return false;
        }
    }

    /**
     * Returns true if the specified item is the first item 
     *
     * @param Model $model
     * @param Int $id
     * @return Boolean, true if it is the first item, false if not
     */
    public function isfirst(&$model, $id = null) {
        if (!$id) {
            if ($model->id) {
                $id = $model->id;
            } elseif (!empty($model->data) && isset($model->data[$model->alias][$model->primaryKey])) {
                $id = $model->id = $model->data[$model->alias][$model->primaryKey];
            } else {
                return false;
            }
        } else {
            $model->id = $id;
        }
        $model->read();

        $first = $this->_read($model, $id);
        if ($model->data[$model->alias][$this->settings[$model->alias]['field']] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns true if the specified item is the last item 
     *
     * @param Model $model
     * @param Int $id
     * @return Boolean, true if it is the last item, false if not
     */
    public function islast(&$model, $id = null) {
        if (!$id) {
            if ($model->id) {
                $id = $model->id;
            } elseif (!empty($model->data) && isset($model->data[$model->alias][$model->primaryKey])) {
                $id = $model->id = $model->data[$model->alias][$model->primaryKey];
            } else {
                return false;
            }
        } else {
            $model->id = $id;
        }
        $model->read();
        $last = $this->_highest($model);
        return ($last[$model->alias][$model->primaryKey] == $id);
    }

    /**
     * Removing an item from the list means to set its field to 0 and updating the other items to be "complete"
     *
     * @param Model $model
     * @param int $id
     * @return boolean 
     */
    public function removefromlist(&$model, $id) {
        $this->_read($model, $id);
        $old_weight = $model->data[$model->alias][$this->settings[$model->alias]['field']];
        $action = array(
            $model->alias . '.' . $this->settings[$model->alias]['field'] => $model->alias . '.' . $this->settings[$model->alias]['field'] . ' - 1');
        $conditions = array(
            $model->alias . '.' . $this->settings[$model->alias]['field'] . ' >' => $old_weight);
        if ($this->settings[$model->alias]['foreign_key']) {
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['foreign_key']] = $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']];
        }
        $data = $model->data;
        $data[$model->alias][$this->settings[$model->alias]['field']] = 0;
        if (!$model->save($data, false)) {
            return false;
        }
        return $model->updateAll($action, $conditions);
    }

    private function _findbyweight(&$model, $weight) {
        $conditions = array($this->settings[$model->alias]['field'] => $weight);
        $fields = array($model->primaryKey, $this->settings[$model->alias]['field']);
        if ($this->settings[$model->alias]['foreign_key']) {
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['foreign_key']] = $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']];
            $fields[] = $this->settings[$model->alias]['foreign_key'];
        }
        return $model->find('first', array(
                    'conditions' => $conditions,
                    'order' => $this->settings[$model->alias]['field'] . ' DESC',
                    'fields' => $fields,
                    'recursive' => -1));
    }

    private function _highest(&$model) {
        $options = array(
            'order' => $this->settings[$model->alias]['field'] . ' DESC',
            'fields' => array($model->primaryKey, $this->settings[$model->alias]['field']),
            'recursive' => -1);
        if ($this->settings[$model->alias]['foreign_key']) {
            if (empty($model->data) || !isset($model->data[$model->alias][$this->settings[$model->alias]['foreign_key']])) {
                $this->_read($model, $model->id);
            }
            $options['conditions'] = array(
                $model->alias . '.' . $this->settings[$model->alias]['foreign_key'] => $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']]);
            $options['fields'][] = $this->settings[$model->alias]['foreign_key'];
        }
        $temp_model_id = $model->id;
        $model->id = null;
        $last = $model->find('first', $options);
        $model->id = $temp_model_id;
        return $last;
    }

    private function _previous(&$model) {
        $conditions = array(
            $this->settings[$model->alias]['field'] => $model->data[$model->alias][$this->settings[$model->alias]['field']] - 1);
        $fields = array($model->primaryKey, $this->settings[$model->alias]['field']);
        if ($this->settings[$model->alias]['foreign_key']) {
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['foreign_key']] = $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']];
            $fields[] = $this->settings[$model->alias]['foreign_key'];
        }
        return $model->find('first', array(
                    'conditions' => $conditions,
                    'order' => $this->settings[$model->alias]['field'] . ' DESC',
                    'fields' => $fields,
                    'recursive' => -1));
    }

    private function _next(&$model) {
        $conditions = array(
            $this->settings[$model->alias]['field'] => $model->data[$model->alias][$this->settings[$model->alias]['field']] + 1);
        $fields = array($model->primaryKey, $this->settings[$model->alias]['field']);
        if ($this->settings[$model->alias]['foreign_key']) {
            $conditions[$model->alias . '.' . $this->settings[$model->alias]['foreign_key']] = $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']];
            $fields[] = $this->settings[$model->alias]['foreign_key'];
        }
        return $model->find('first', array(
                    'conditions' => $conditions,
                    'order' => $this->settings[$model->alias]['field'] . ' DESC',
                    'fields' => $fields,
                    'recursive' => -1));
    }

    private function _all(&$model) {
        $options = array(
            'order' => $this->settings[$model->alias]['field'] . ' DESC',
            'fields' => array($model->primaryKey, $this->settings[$model->alias]['field']),
            'recursive' => -1);
        if ($this->settings[$model->alias]['foreign_key']) {
            $options['conditions'] = array(
                $this->settings[$model->alias]['foreign_key'] => $model->data[$model->alias][$this->settings[$model->alias]['foreign_key']]);
            $options['fields'][] = $this->settings[$model->alias]['foreign_key'];
        }
        return $model->find('all', $options);
    }

    private function _read(&$model, $id) {
        $model->id = $id;
        $fields = array($model->primaryKey, $this->settings[$model->alias]['field']);
        if ($this->settings[$model->alias]['foreign_key']) {
            $fields[] = $this->settings[$model->alias]['foreign_key'];
        }
        $model->data = $model->find('first', array(
            'fields' => $fields,
            'conditions' => array($model->primaryKey => $id),
            'recursive' => -1));
        return $model->data[$model->alias][$this->settings[$model->alias]['field']];
    }

}

?>
