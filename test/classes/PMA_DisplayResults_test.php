<?php
/**
 * Tests for displaing results
 *
 * @package PhpMyAdmin-test
 */

/*
 * Include to test.
 */
require_once 'libraries/DisplayResults.class.php';
require_once 'libraries/url_generating.lib.php';
require_once 'libraries/php-gettext/gettext.inc';
require_once 'libraries/CommonFunctions.class.php';
require_once 'libraries/js_escape.lib.php';

/**
 * Test cases for displaying results.
 *
 * @package PhpMyAdmin-test
 */
class PMA_DisplayResults_test extends PHPUnit_Framework_TestCase
{
    /**
     * @access protected
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     * @return void
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass(
            'PMA_DisplayResults',
            array('as', '','','')
        );

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     * @return void
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * Call private functions by making the visibitlity to public.
     *
     * @param string $name   method name
     * @param array  $params parameters for the invocation
     *
     * @return the output from the private method.
     */
    private function _callPrivateFunction($name, $params)
    {
        $class = new ReflectionClass('PMA_DisplayResults');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($this->object, $params);
    }

    /**
     * Test for setting display mode
     *
     * @param string  $the_disp_mode the synthetic value for display_mode (see a
     *                               few lines above for explanations)
     * @param integer $the_total     the total number of rows returned by the SQL
     *                               query without any programmatically appended
     *                               LIMIT clause
     *                               (just a copy of $unlim_num_rows if it exists,
     *                               elsecomputed inside this function)
     * @param string  $output        output from the _setDisplayMode method
     *
     * @dataProvider providerForTestSetDisplayModeCase1
     */
    public function testSetDisplayModeCase1($the_disp_mode, $the_total, $output)
    {

        if (!isset($GLOBALS['fields_meta'])) {
            $fields_meta = array();
            $fields_meta[0] = new stdClass();
            $fields_meta[0]->table = 'company';
        } else {
            $fields_meta = $GLOBALS['fields_meta'];
        }

        $this->object->setProperties(
            null, $fields_meta, true, null, null,
            null, null, null, null, null, null,
            true, null, null, null, null, null
        );

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_setDisplayMode',
                array(&$the_disp_mode, &$the_total)
            )
        );
    }

    /**
     * @return array data for testSetDisplayModeCase1
     */
    public function providerForTestSetDisplayModeCase1()
    {
        return array(
            array(
                'urkp111111',
                5,
                array(
                    'edit_lnk' => 'nn',
                    'del_lnk' => 'nn',
                    'sort_lnk' => 0,
                    'nav_bar' => 0,
                    'ins_row' => 0,
                    'bkm_form' => 1,
                    'text_btn' => 1,
                    'pview_lnk' => 1
                )
            ),
            array(
                'nnnn000000',
                5,
                array(
                    'edit_lnk' => 'nn',
                    'del_lnk' => 'nn',
                    'sort_lnk' => 0,
                    'nav_bar' => 0,
                    'ins_row' => 0,
                    'bkm_form' => 0,
                    'text_btn' => 0,
                    'pview_lnk' => 0
                )
            )
        );
    }

    /**
     * Test for setting display mode
     *
     * @param string  $the_disp_mode the synthetic value for display_mode (see a
     *                               few lines above for explanations)
     * @param integer $the_total     the total number of rows returned by the SQL
     *                               query without any programmatically appended
     *                               LIMIT clause
     *                               (just a copy of $unlim_num_rows if it exists,
     *                               elsecomputed inside this function)
     * @param string  $output        output from the _setDisplayMode method
     *
     * @dataProvider providerForTestSetDisplayModeCase2
     */
    public function testSetDisplayModeCase2($the_disp_mode, $the_total, $output)
    {

        if (!isset($GLOBALS['fields_meta'])) {
            $fields_meta = array();
            $fields_meta[0] = new stdClass();
            $fields_meta[0]->table = 'company';
        } else {
            $fields_meta = $GLOBALS['fields_meta'];
        }

        $this->object->setProperties(
            1, $fields_meta, false, null, null,
            false, null, null, null, null, null,
            false, false, true, null, null, null
        );

        $this->object->__set('_sql_query', 'SELECT * FROM `pma_bookmark` WHERE 1');

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_setDisplayMode',
                array(&$the_disp_mode, &$the_total)
            )
        );
    }

    /**
     * @return array data for testSetDisplayModeCase2
     */
    public function providerForTestSetDisplayModeCase2()
    {
        return array(
            array(
                'urkp111111',
                5,
                array(
                    'edit_lnk' => 'nn',
                    'del_lnk' => 'nn',
                    'sort_lnk' => 0,
                    'nav_bar' => 0,
                    'ins_row' => 0,
                    'bkm_form' => 1,
                    'text_btn' => 1,
                    'pview_lnk' => 1
                )
            ),
            array(
                'nnnn000000',
                5,
                array(
                    'edit_lnk' => 'nn',
                    'del_lnk' => 'nn',
                    'sort_lnk' => 0,
                    'nav_bar' => 0,
                    'ins_row' => 0,
                    'bkm_form' => 0,
                    'text_btn' => 0,
                    'pview_lnk' => 0
                )
            )
        );
    }

    /**
     * Test for setting display mode
     *
     * @param string  $the_disp_mode the synthetic value for display_mode (see a
     *                               few lines above for explanations)
     * @param integer $the_total     the total number of rows returned by the SQL
     *                               query without any programmatically appended
     *                               LIMIT clause
     *                               (just a copy of $unlim_num_rows if it exists,
     *                               elsecomputed inside this function)
     * @param string  $output        output from the _setDisplayMode method
     *
     * @dataProvider providerForTestSetDisplayModeCase3
     */
    public function testSetDisplayModeCase3($the_disp_mode, $the_total, $output)
    {

        if (!isset($GLOBALS['fields_meta'])) {
            $fields_meta = array();
            $fields_meta[0] = new stdClass();
            $fields_meta[0]->table = 'company';
        } else {
            $fields_meta = $GLOBALS['fields_meta'];
        }

        $this->object->setProperties(
            1, $fields_meta, false, null, null,
            false, null, null, null, null, null,
            false, false, null, null, '1', null
        );

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_setDisplayMode',
                array(&$the_disp_mode, &$the_total)
            )
        );
    }

    /**
     * @return array data for testSetDisplayModeCase3
     */
    public function providerForTestSetDisplayModeCase3()
    {
        return array(
            array(
                'urkp111111',
                5,
                array(
                    'edit_lnk' => 'nn',
                    'del_lnk' => 'nn',
                    'sort_lnk' => 0,
                    'nav_bar' => 0,
                    'ins_row' => 0,
                    'bkm_form' => 0,
                    'text_btn' => 0,
                    'pview_lnk' => 0
                )
            ),
            array(
                'nnnn000000',
                5,
                array(
                    'edit_lnk' => 'nn',
                    'del_lnk' => 'nn',
                    'sort_lnk' => 0,
                    'nav_bar' => 0,
                    'ins_row' => 0,
                    'bkm_form' => 0,
                    'text_btn' => 0,
                    'pview_lnk' => 0
                )
            )
        );
    }

    /**
     * Test for _isSelect function
     */
    public function testisSelect()
    {

        $analyzed_sql[0]['select_expr'] = array();
        $analyzed_sql[0]['queryflags']['select_from'] = 'pma';
        $analyzed_sql[0]['table_ref'] = array('table_ref');

        $this->assertTrue(
            $this->_callPrivateFunction(
                '_isSelect',
                array($analyzed_sql)
            )
        );
    }

    /**
     * @param string  $caption        iconic caption for button
     * @param string  $title          text for button
     * @param integer $pos            position for next query
     * @param string  $html_sql_query query ready for display
     * @param string  $output         output from the _getTableNavigationButton
     *                                method
     *
     * @dataProvider providerForTestGetTableNavigationButton
     */
    public function testGetTableNavigationButton(
        $caption, $title, $pos, $html_sql_query, $output
    ) {

        $GLOBALS['cfg']['NavigationBarIconic'] = true;
        $GLOBALS['cfg']['AjaxEnable'] = true;
        $_SESSION[' PMA_token '] = 'token';

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getTableNavigationButton',
                array(&$caption, $title, $pos, $html_sql_query)
            )
        );
    }

    /**
     * @return array array data for testGetTableNavigationButton
     */
    public function providerForTestGetTableNavigationButton()
    {
        return array(
            array(
                'btn',
                'Submit',
                1,
                'SELECT * FROM `pma_bookmark` WHERE 1',
                '<td><form action="sql.php" method="post" ><input type="hidden" name="db" value="as" /><input type="hidden" name="token" value="token" /><input type="hidden" name="sql_query" value="SELECT * FROM `pma_bookmark` WHERE 1" /><input type="hidden" name="pos" value="1" /><input type="hidden" name="goto" value="" /><input type="submit" name="navig" class="ajax" value="btn"  title="Submit" /></form></td>'
            )
        );
    }

    /**
     * @param integer $pos_next                  the offset for the "next" page
     * @param integer $pos_prev                  the offset for the "previous" page
     * @param string  $id_for_direction_dropdown the id for the direction dropdown
     * @param boolean $is_innodb                 the table type is innoDb or not
     * @param string  $output                    output from the _getTableNavigation
     *                                           method
     *
     * @dataProvider providerForTestGetTableNavigation
     */
    public function testGetTableNavigation(
        $pos_next, $pos_prev, $id_for_direction_dropdown, $is_innodb, $output
    ) {

        $_SESSION['tmp_user_values']['max_rows'] = '20';
        $GLOBALS['cfg']['AjaxEnable'] = true;
        $_SESSION['tmp_user_values']['pos'] = true;
        $GLOBALS['num_rows'] = '20';
        $GLOBALS['unlim_num_rows'] = '50';
        $GLOBALS['cfg']['ShowAll'] = true;
        $GLOBALS['cfg']['ShowDisplayDirection'] = true;
        $_SESSION['tmp_user_values']['repeat_cells'] = '1';
        $_SESSION['tmp_user_values']['disp_direction'] = '1';

        $this->assertEquals(
            $output,
            str_word_count(
                $this->_callPrivateFunction(
                    '_getTableNavigation',
                    array(
                        $pos_next, $pos_prev, $id_for_direction_dropdown, $is_innodb
                    )
                )
            )
        );
    }

    /**
     * @return array data for testGetTableNavigation
     */
    public function providerForTestGetTableNavigation()
    {
        return array(
            array(
                21,
                41,
                '123',
                false,
                '309'
            )
        );
    }

    /**
     * Data provider for testGetResettedClassForInlineEdit
     *
     * @return array parameters and output
     */
    public function dataProviderForTestGetResettedClassForInlineEdit()
    {
        return array(
            array(
                'grid_edit',
                'not_null',
                '',
                '',
                '',
                0,
                'data grid_edit not_null    row_0 vpointer vmarker'
            )
        );
    }

    /**
     * Test for _getResettedClassForInlineEdit
     *
     * @param string  $grid_edit_class  the class for all editable columns
     * @param string  $not_null_class   the class for not null columns
     * @param string  $relation_class   the class for relations in a column
     * @param string  $hide_class       the class for visibility of a column
     * @param string  $field_type_class the class related to type of the field
     * @param integer $row_no           the row index
     * @param string  $output           output of__getResettedClassForInlineEdit
     *
     * @dataProvider dataProviderForTestGetResettedClassForInlineEdit
     */
    public function testGetResettedClassForInlineEdit(
        $grid_edit_class, $not_null_class, $relation_class,
        $hide_class, $field_type_class, $row_no, $output
    ) {

        $GLOBALS['cfg']['BrowsePointerEnable'] = true;
        $GLOBALS['cfg']['BrowseMarkerEnable'] = true;
        $_SESSION['tmp_user_values']['disp_direction']
            = PMA_DisplayResults::DISP_DIR_VERTICAL;

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getResettedClassForInlineEdit',
                array(
                    $grid_edit_class, $not_null_class, $relation_class,
                    $hide_class, $field_type_class, $row_no
                )
            )
        );
    }

    /**
     * Test for _getClassForDateTimeRelatedFields - case 1
     */
    public function testGetClassForDateTimeRelatedFieldsCase1()
    {
        $this->assertEquals(
            'datetimefield',
            $this->_callPrivateFunction(
                '_getClassForDateTimeRelatedFields',
                array(PMA_DisplayResults::DATETIME_FIELD)
            )
        );
    }

    /**
     * Test for _getClassForDateTimeRelatedFields - case 2
     */
    public function testGetClassForDateTimeRelatedFieldsCase2()
    {
        $this->assertEquals(
            'datefield',
            $this->_callPrivateFunction(
                '_getClassForDateTimeRelatedFields',
                array(PMA_DisplayResults::DATE_FIELD)
            )
        );
    }

    /**
     * Test for _getClassForDateTimeRelatedFields - case 3
     */
    public function testGetClassForDateTimeRelatedFieldsCase3()
    {
        $this->assertEquals(
            '',
            $this->_callPrivateFunction(
                '_getClassForDateTimeRelatedFields',
                array(PMA_DisplayResults::STRING_FIELD)
            )
        );
    }

    /**
     * Provide data for testGetOperationLinksForVerticleTableCase1
     *
     * @return array parameters and output
     */
    public function dataProviderForTestGetOperationLinksForVerticleTableCase1()
    {
        return array(
            array(
                'edit',
                '<tr>
</tr>
'
            )
        );
    }

    /**
     * Test for _getOperationLinksForVerticleTable - case 1
     *
     * @param string $operation        edit/copy/delete
     * @param string $output           output of _getOperationLinksForVerticleTable
     *
     * @dataProvider dataProviderForTestGetOperationLinksForVerticleTableCase1
     */
    public function testGetOperationLinksForVerticleTableCase1(
        $operation, $output
    ) {

        $vertical_display = array(
            'row_delete' => array(),
            'textbtn' => '<th  rowspan="4" class="vmiddle">\n        \n    </th>\n',
            'edit' => array(),
            'copy' => array(
                '<td class="odd row_0 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+3&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+3" /></spa',
                '<td class="even row_1 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+9&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+9" /></sp'
            ),
            'delete' => array(
                '<td class="odd row_0 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+3&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this,',
                '<td class="even row_1 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+9&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this'
            )
        );

        $this->object->__set('_vertical_display', $vertical_display);

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getOperationLinksForVerticleTable',
                array($operation)
            )
        );
    }

    /**
     * Provide data for testGetOperationLinksForVerticleTableCase2
     *
     * @return array parameters and output
     */
    public function dataProviderForTestGetOperationLinksForVerticleTableCase2()
    {
        return array(
            array(
                'copy',
                '<tr>
<td class="odd row_0 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+3&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+3" /></spa<td class="even row_1 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+9&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+9" /></sp</tr>
'
            )
        );
    }

    /**
     * Test for _getOperationLinksForVerticleTable - case 2
     *
     * @param string $operation        edit/copy/delete
     * @param string $output           output of _getOperationLinksForVerticleTable
     *
     * @dataProvider dataProviderForTestGetOperationLinksForVerticleTableCase2
     */
    public function testGetOperationLinksForVerticleTableCase2(
        $operation, $output
    ) {

        $vertical_display = array(
            'row_delete' => array(),
            'textbtn' => '<th  rowspan="4" class="vmiddle">\n        \n    </th>\n',
            'edit' => array(),
            'copy' => array(
                '<td class="odd row_0 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+3&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+3" /></spa',
                '<td class="even row_1 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+9&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+9" /></sp'
            ),
            'delete' => array(
                '<td class="odd row_0 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+3&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this,',
                '<td class="even row_1 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+9&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this'
            )
        );

        $this->object->__set('_vertical_display', $vertical_display);

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getOperationLinksForVerticleTable',
                array($operation)
            )
        );
    }


    /**
     * Provide data for testGetOperationLinksForVerticleTableCase3
     *
     * @return array parameters and output
     */
    public function dataProviderForTestGetOperationLinksForVerticleTableCase3()
    {
        return array(
            array(
                'delete',
                '<tr>
<td class="odd row_0 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+3&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this,<td class="even row_1 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+9&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this</tr>
'
            )
        );
    }

    /**
     * Test for _getOperationLinksForVerticleTable - case 3
     *
     * @param string $operation        edit/copy/delete
     * @param string $output           output of _getOperationLinksForVerticleTable
     *
     * @dataProvider dataProviderForTestGetOperationLinksForVerticleTableCase3
     */
    public function testGetOperationLinksForVerticleTableCase3(
        $operation, $output
    ) {

        $vertical_display = array(
            'row_delete' => array(),
            'textbtn' => '<th  rowspan="4" class="vmiddle">\n        \n    </th>\n',
            'edit' => array(),
            'copy' => array(
                '<td class="odd row_0 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+3&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+3" /></spa',
                '<td class="even row_1 vpointer vmarker center"  ><span class="nowrap">\n<a href="tbl_change.php?db=Data&amp;table=cars&amp;where_clause=%60cars%60.%60id%60+%3D+9&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60cars%60&amp;goto=sql.php&amp;default_action=insert&amp;token=466f137b5f4556e43103245a086fc001" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>\n<input type="hidden" class="where_clause" value="%60cars%60.%60id%60+%3D+9" /></sp'
            ),
            'delete' => array(
                '<td class="odd row_0 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+3&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this,',
                '<td class="even row_1 vpointer vmarker center"  >\n<a href="sql.php?db=Data&amp;table=cars&amp;sql_query=DELETE+FROM+%60Data%60.%60cars%60+WHERE+%60cars%60.%60id%60+%3D+9&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcars%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560cars%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3D466f137b5f4556e43103245a086fc001&amp;token=466f137b5f4556e43103245a086fc001" onclick="return confirmLink(this'
            )
        );

        $this->object->__set('_vertical_display', $vertical_display);

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getOperationLinksForVerticleTable',
                array($operation)
            )
        );
    }

    /**
     * Data provider for testGetCheckBoxesForMultipleRowOperations
     *
     * @return array parameters and output
     */
    public function dataProviderForGetCheckBoxesForMultipleRowOperations()
    {
        return array(
            array(
                '_left',
                '<td class="odd row_0 vpointer vmarker" class="center"><input type="checkbox" id="id_rows_to_delete0_left" name="rows_to_delete[0]" class="multi_checkbox" value="%60cars%60.%60id%60+%3D+3"  /><input type="hidden" class="condition_array" value="{&quot;`cars`.`id`&quot;:&quot;= 3&quot;}" />    </td><td class="even row_1 vpointer vmarker" class="center"><input type="checkbox" id="id_rows_to_delete1_left" name="rows_to_delete[1]" class="multi_checkbox" value="%60cars%60.%60id%60+%3D+9"  /><input type="hidden" class="condition_array" value="{&quot;`cars`.`id`&quot;:&quot;= 9&quot;}" />    </td>'
            )
        );
    }

    /**
     * Test for _getCheckBoxesForMultipleRowOperations
     *
     * @param array  $vertical_display the information to display
     * @param string $dir              _left / _right
     * @param string $output           output of _getCheckBoxesForMultipleRowOperations
     *
     * @dataProvider dataProviderForGetCheckBoxesForMultipleRowOperations
     */
    public function testGetCheckBoxesForMultipleRowOperations(
        $dir, $output
    ) {

        $vertical_display = array(
            'row_delete' => array(
                '<td class="odd row_0 vpointer vmarker" class="center"><input type="checkbox" id="id_rows_to_delete0[%_PMA_CHECKBOX_DIR_%]" name="rows_to_delete[0]" class="multi_checkbox" value="%60cars%60.%60id%60+%3D+3"  /><input type="hidden" class="condition_array" value="{&quot;`cars`.`id`&quot;:&quot;= 3&quot;}" />    </td>',
                '<td class="even row_1 vpointer vmarker" class="center"><input type="checkbox" id="id_rows_to_delete1[%_PMA_CHECKBOX_DIR_%]" name="rows_to_delete[1]" class="multi_checkbox" value="%60cars%60.%60id%60+%3D+9"  /><input type="hidden" class="condition_array" value="{&quot;`cars`.`id`&quot;:&quot;= 9&quot;}" />    </td>'
            )
        );

        $this->object->__set('_vertical_display', $vertical_display);

        $_SESSION['tmp_user_values']['repeat_cells'] = 0;
        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getCheckBoxesForMultipleRowOperations',
                array($dir)
            )
        );
    }

    /**
     * Test for _getOffsets - case 1
     */
    public function testGetOffsetsCase1()
    {
        $_SESSION['tmp_user_values']['max_rows'] = PMA_DisplayResults::ALL_ROWS;
        $this->assertEquals(
            array(0, 0),
            $this->_callPrivateFunction('_getOffsets', array())
        );
    }

    /**
     * Test for _getOffsets - case 2
     */
    public function testGetOffsetsCase2()
    {
        $_SESSION['tmp_user_values']['max_rows'] = 5;
        $_SESSION['tmp_user_values']['pos'] = 4;
        $this->assertEquals(
            array(9, 0),
            $this->_callPrivateFunction('_getOffsets', array())
        );
    }

    /**
     * Data provider for testGetSortParamsCase1
     *
     * @return array parameters and output
     */
    public function dataProviderForGetSortParamsCase1()
    {
        return array(
            array('', array('', '', ''))
        );
    }

    /**
     * Test for _getSortParams - case 1
     *
     * @param string $order_by_clause the order by clause of the sql query
     * @param string $output          output of _getSortParams
     *
     * @dataProvider dataProviderForGetSortParamsCase1
     */
    public function testGetSortParamsCase1($order_by_clause, $output)
    {
        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getSortParams', array($order_by_clause)
            )
        );
    }

    /**
     * Data provider for testGetSortParamsCase2
     *
     * @return array parameters and output
     */
    public function dataProviderForGetSortParamsCase2()
    {
        return array(
            array(
                '`a_sales`.`customer_id` ASC',
                array(
                    '`a_sales`.`customer_id` ASC',
                    '`a_sales`.`customer_id`',
                    'ASC'
                )
            )
        );
    }

    /**
     * Test for _getSortParams - case 2
     *
     * @param string $order_by_clause the order by clause of the sql query
     * @param string $output          output of _getSortParams
     *
     * @dataProvider dataProviderForGetSortParamsCase2
     */
    public function testGetSortParamsCase2($order_by_clause, $output)
    {
        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getSortParams', array($order_by_clause)
            )
        );
    }

    /**
     * Data provider for testGetCheckboxForMultiRowSubmissions
     *
     * @return array parameters and output
     */
    public function dataProviderForGetCheckboxForMultiRowSubmissions()
    {
        return array(
            array(
                'sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dd1aecb47ef7c081e068e7008b38a5d76&amp;token=d1aecb47ef7c081e068e7008b38a5d76',
                array(
                    'edit_lnk' => 'ur',
                    'del_lnk' => 'dr',
                    'sort_lnk' => '0',
                    'nav_bar' => '1',
                    'ins_row' => '1',
                    'bkm_form' => '1',
                    'text_btn' => '1',
                    'pview_lnk' => '1'
                ),
                0,
                '%60new%60.%60id%60+%3D+1',
                array('`new`.`id`' => '= 1'),
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                '[%_PMA_CHECKBOX_DIR_%]',
                'odd row_0 vpointer vmarker',
                '<td class="odd row_0 vpointer vmarker" class="center"><input type="checkbox" id="id_rows_to_delete0[%_PMA_CHECKBOX_DIR_%]" name="rows_to_delete[0]" class="multi_checkbox" value="%60new%60.%60id%60+%3D+1"  /><input type="hidden" class="condition_array" value="{&quot;`new`.`id`&quot;:&quot;= 1&quot;}" />    </td>'
            )
        );
    }

    /**
     * Test for _getCheckboxForMultiRowSubmissions
     *
     * @param string $del_url           delete url
     * @param array  $is_display        array with explicit indexes for all
     *                                  the display elements
     * @param string $row_no            the row number
     * @param string $where_clause_html url encoded where clause
     * @param array  $condition_array   array of conditions in the where clause
     * @param string $del_query         delete query
     * @param string $id_suffix         suffix for the id
     * @param string $class             css classes for the td element
     * @param string $output            output of _getSortParams
     * @param string $output            output of _getCheckboxForMultiRowSubmissions
     *
     * @dataProvider dataProviderForGetCheckboxForMultiRowSubmissions
     */
    public function testGetCheckboxForMultiRowSubmissions(
        $del_url, $is_display, $row_no, $where_clause_html, $condition_array,
        $del_query, $id_suffix, $class, $output
    ) {
        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getCheckboxForMultiRowSubmissions',
                array(
                    $del_url, $is_display, $row_no, $where_clause_html,
                    $condition_array, $del_query, $id_suffix, $class
                )
            )
        );
    }

    /**
     * Data provider for testGetEditLink
     *
     * @return array parametres and output
     */
    public function dataProviderForGetEditLink()
    {
        return array(
            array(
                'tbl_change.php?db=Data&amp;table=customer&amp;where_clause=%60customer%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60customer%60&amp;goto=sql.php&amp;default_action=update&amp;token=bbd5003198a3bd856b21d9607d6c6a1e',
                'odd edit_row_anchor row_0 vpointer vmarker',
                '<span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span>',
                '`customer`.`id` = 1',
                '%60customer%60.%60id%60+%3D+1',
                '<td class="odd edit_row_anchor row_0 vpointer vmarker center"  ><span class="nowrap">
<a href="tbl_change.php?db=Data&amp;table=customer&amp;where_clause=%60customer%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60customer%60&amp;goto=sql.php&amp;default_action=update&amp;token=bbd5003198a3bd856b21d9607d6c6a1e" ><span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span></a>
<input type="hidden" class="where_clause" value ="%60customer%60.%60id%60+%3D+1" /></span></td>'
            )
        );
    }

    /**
     * Test for _getEditLink
     *
     * @param string $edit_url          edit url
     * @param string $class             css classes for td element
     * @param string $edit_str          text for the edit link
     * @param string $where_clause      where clause
     * @param string $where_clause_html url encoded where clause
     * @param string $output            output of _getEditLink
     *
     * @dataProvider dataProviderForGetEditLink
     */
    public function testGetEditLink(
        $edit_url, $class, $edit_str, $where_clause, $where_clause_html, $output
    ) {

        $GLOBALS['cfg']['PropertiesIconic'] = 'both';
        $GLOBALS['cfg']['LinkLengthLimit'] = 1000;

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getEditLink',
                array(
                    $edit_url, $class, $edit_str, $where_clause, $where_clause_html
                )
            )
        );

    }

    /**
     * Data provider for testGetCopyLink
     *
     * @return array parameters and output
     */
    public function dataProviderForGetCopyLink()
    {
        return array(
            array(
                'tbl_change.php?db=Data&amp;table=customer&amp;where_clause=%60customer%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60customer%60&amp;goto=sql.php&amp;default_action=insert&amp;token=f597309d3a066c3c81a6cb015a79636d',
                '<span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span>',
                '`customer`.`id` = 1',
                '%60customer%60.%60id%60+%3D+1',
                'odd row_0 vpointer vmarker',
                '<td class="odd row_0 vpointer vmarker center"  ><span class="nowrap">
<a href="tbl_change.php?db=Data&amp;table=customer&amp;where_clause=%60customer%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60customer%60&amp;goto=sql.php&amp;default_action=insert&amp;token=f597309d3a066c3c81a6cb015a79636d" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>
<input type="hidden" class="where_clause" value="%60customer%60.%60id%60+%3D+1" /></span></td>'
            )
        );
    }

    /**
     * Test for _getCopyLink
     *
     * @param string $copy_url          copy url
     * @param string $copy_str          text for the copy link
     * @param string $where_clause      where clause
     * @param string $where_clause_html url encoded where clause
     * @param string $class             css classes for the td element
     * @param string $output            output of _getCopyLink
     *
     * @dataProvider dataProviderForGetCopyLink
     */
    public function testGetCopyLink(
        $copy_url, $copy_str, $where_clause, $where_clause_html, $class, $output
    ) {

        $GLOBALS['cfg']['PropertiesIconic'] = 'both';
        $GLOBALS['cfg']['LinkLengthLimit'] = 1000;

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getCopyLink',
                array(
                    $copy_url, $copy_str, $where_clause, $where_clause_html, $class
                )
            )
        );
    }

    /**
     * Data provider for testGetDeleteLink
     *
     * @return array parameters and output
     */
    public function dataProviderForGetDeleteLink()
    {
        return array(
            array(
                'sql.php?db=Data&amp;table=customer&amp;sql_query=DELETE+FROM+%60Data%60.%60customer%60+WHERE+%60customer%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcustomer%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560customer%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Df597309d3a066c3c81a6cb015a79636d&amp;token=f597309d3a066c3c81a6cb015a79636d',
                '<span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span>',
                'DELETE FROM `Data`.`customer` WHERE `customer`.`id` = 1',
                'odd row_0 vpointer vmarker',
                '<td class="odd row_0 vpointer vmarker center"  >
<a href="sql.php?db=Data&amp;table=customer&amp;sql_query=DELETE+FROM+%60Data%60.%60customer%60+WHERE+%60customer%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3DData%26table%3Dcustomer%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560customer%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Df597309d3a066c3c81a6cb015a79636d&amp;token=f597309d3a066c3c81a6cb015a79636d" onclick="return confirmLink(this, \'DELETE FROM `Data`.`customer` WHERE `customer`.`id` = 1\')"><span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span></a>
</td>'
            )
        );
    }

    /**
     * Test for _getDeleteLink
     *
     * @param string $del_url delete url
     * @param string $del_str text for the delete link
     * @param string $js_conf text for the JS confirmation
     * @param string $class   css classes for the td element
     * @param string $output  output of _getDeleteLink
     *
     * @dataProvider dataProviderForGetDeleteLink
     */
    public function testGetDeleteLink(
        $del_url, $del_str, $js_conf, $class, $output
    ) {

        $GLOBALS['cfg']['PropertiesIconic'] = 'both';
        $GLOBALS['cfg']['LinkLengthLimit'] = 1000;

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getDeleteLink',
                array(
                    $del_url, $del_str, $js_conf, $class
                )
            )
        );
    }

    /**
     * Data provider for testGetCheckboxAndLinksCase1
     *
     * @return array parameters and output
     */
    public function dataProviderForGetCheckboxAndLinksCase1()
    {
        return array(
            array(
                PMA_DisplayResults::POSITION_LEFT,
                'sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dae4c6d18375f446dfa068420c1f6a4e8&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                array(
                    'edit_lnk' => 'ur',
                    'del_lnk' => 'dr',
                    'sort_lnk' => '0',
                    'nav_bar' => '1',
                    'ins_row' => '1',
                    'bkm_form' => '1',
                    'text_btn' => '1',
                    'pview_lnk' => '1'
                ),
                0,
                '`new`.`id` = 1',
                '%60new%60.%60id%60+%3D+1',
                array(
                    '`new`.`id`' => '= 1',
                ),
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                'l',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=update&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=insert&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'edit_row_anchor',
                '<span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span>',
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                '<td  class="center"><input type="checkbox" id="id_rows_to_delete0_left" name="rows_to_delete[0]" class="multi_checkbox" value="%60new%60.%60id%60+%3D+1"  /><input type="hidden" class="condition_array" value="{&quot;`new`.`id`&quot;:&quot;= 1&quot;}" />    </td><td class="edit_row_anchor center"  ><span class="nowrap">
<a href="tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=update&amp;token=ae4c6d18375f446dfa068420c1f6a4e8" ><span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span></a>
<input type="hidden" class="where_clause" value ="%60new%60.%60id%60+%3D+1" /></span></td><td class="center"  ><span class="nowrap">
<a href="tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=insert&amp;token=ae4c6d18375f446dfa068420c1f6a4e8" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>
<input type="hidden" class="where_clause" value="%60new%60.%60id%60+%3D+1" /></span></td><td class="center"  >
<a href="sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dae4c6d18375f446dfa068420c1f6a4e8&amp;token=ae4c6d18375f446dfa068420c1f6a4e8" onclick="return confirmLink(this, \'DELETE FROM `data`.`new` WHERE `new`.`id` = 1\')"><span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span></a>
</td>'
            )
        );
    }

    /**
     * Test for _getCheckboxAndLinks - case 1
     *
     * @param string $position          the position of the checkbox and links
     * @param string $del_url           delete url
     * @param array  $is_display        array with explicit indexes for all the
     *                                  display elements
     * @param string $row_no            row number
     * @param string $where_clause      where clause
     * @param string $where_clause_html url encoded where clause
     * @param array  $condition_array   array of conditions in the where clause
     * @param string $del_query         delete query
     * @param string $id_suffix         suffix for the id
     * @param string $edit_url          edit url
     * @param string $copy_url          copy url
     * @param string $class             css classes for the td elements
     * @param string $edit_str          text for the edit link
     * @param string $copy_str          text for the copy link
     * @param string $del_str           text for the delete link
     * @param string $js_conf           text for the JS confirmation
     * @param string $output            output of _getCheckboxAndLinks
     *
     * @dataProvider dataProviderForGetCheckboxAndLinksCase1
     */
    public function testGetCheckboxAndLinksCase1(
        $position, $del_url, $is_display, $row_no, $where_clause,
        $where_clause_html, $condition_array, $del_query, $id_suffix, $edit_url,
        $copy_url, $class, $edit_str, $copy_str, $del_str, $js_conf, $output
    ) {

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getCheckboxAndLinks',
                array(
                    $position, $del_url, $is_display, $row_no, $where_clause,
                    $where_clause_html, $condition_array, $del_query,
                    $id_suffix, $edit_url, $copy_url, $class, $edit_str,
                    $copy_str, $del_str, $js_conf
                )
            )
        );
    }

    /**
     * Data provider for testGetCheckboxAndLinksCase2
     *
     * @return array parameters and output
     */
    public function dataProviderForGetCheckboxAndLinksCase2()
    {
        return array(
            array(
                PMA_DisplayResults::POSITION_RIGHT,
                'sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dae4c6d18375f446dfa068420c1f6a4e8&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                array(
                    'edit_lnk' => 'ur',
                    'del_lnk' => 'dr',
                    'sort_lnk' => '0',
                    'nav_bar' => '1',
                    'ins_row' => '1',
                    'bkm_form' => '1',
                    'text_btn' => '1',
                    'pview_lnk' => '1'
                ),
                0,
                '`new`.`id` = 1',
                '%60new%60.%60id%60+%3D+1',
                array(
                    '`new`.`id`' => '= 1',
                ),
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                'l',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=update&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=insert&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'edit_row_anchor',
                '<span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span>',
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                '<td class="center"  >
<a href="sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dae4c6d18375f446dfa068420c1f6a4e8&amp;token=ae4c6d18375f446dfa068420c1f6a4e8" onclick="return confirmLink(this, \'DELETE FROM `data`.`new` WHERE `new`.`id` = 1\')"><span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span></a>
</td><td class="center"  ><span class="nowrap">
<a href="tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=insert&amp;token=ae4c6d18375f446dfa068420c1f6a4e8" ><span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span></a>
<input type="hidden" class="where_clause" value="%60new%60.%60id%60+%3D+1" /></span></td><td class="edit_row_anchor center"  ><span class="nowrap">
<a href="tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=update&amp;token=ae4c6d18375f446dfa068420c1f6a4e8" ><span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span></a>
<input type="hidden" class="where_clause" value ="%60new%60.%60id%60+%3D+1" /></span></td><td  class="center"><input type="checkbox" id="id_rows_to_delete0_right" name="rows_to_delete[0]" class="multi_checkbox" value="%60new%60.%60id%60+%3D+1"  /><input type="hidden" class="condition_array" value="{&quot;`new`.`id`&quot;:&quot;= 1&quot;}" />    </td>'
            )
        );
    }

    /**
     * Test for _getCheckboxAndLinks - case 2
     *
     * @param string $position          the position of the checkbox and links
     * @param string $del_url           delete url
     * @param array  $is_display        array with explicit indexes for all the
     *                                  display elements
     * @param string $row_no            row number
     * @param string $where_clause      where clause
     * @param string $where_clause_html url encoded where clause
     * @param array  $condition_array   array of conditions in the where clause
     * @param string $del_query         delete query
     * @param string $id_suffix         suffix for the id
     * @param string $edit_url          edit url
     * @param string $copy_url          copy url
     * @param string $class             css classes for the td elements
     * @param string $edit_str          text for the edit link
     * @param string $copy_str          text for the copy link
     * @param string $del_str           text for the delete link
     * @param string $js_conf           text for the JS confirmation
     * @param string $output            output of _getCheckboxAndLinks
     *
     * @dataProvider dataProviderForGetCheckboxAndLinksCase2
     */
    public function testGetCheckboxAndLinksCase2(
        $position, $del_url, $is_display, $row_no, $where_clause,
        $where_clause_html, $condition_array, $del_query, $id_suffix, $edit_url,
        $copy_url, $class, $edit_str, $copy_str, $del_str, $js_conf, $output
    ) {

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getCheckboxAndLinks',
                array(
                    $position, $del_url, $is_display, $row_no, $where_clause,
                    $where_clause_html, $condition_array, $del_query,
                    $id_suffix, $edit_url, $copy_url, $class, $edit_str,
                    $copy_str, $del_str, $js_conf
                )
            )
        );
    }

    /**
     * Data provider for testGetCheckboxAndLinksCase3
     *
     * @return array parameters and output
     */
    public function dataProviderForGetCheckboxAndLinksCase3()
    {
        return array(
            array(
                PMA_DisplayResults::POSITION_NONE,
                'sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dae4c6d18375f446dfa068420c1f6a4e8&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                array(
                    'edit_lnk' => 'ur',
                    'del_lnk' => 'dr',
                    'sort_lnk' => '0',
                    'nav_bar' => '1',
                    'ins_row' => '1',
                    'bkm_form' => '1',
                    'text_btn' => '1',
                    'pview_lnk' => '1'
                ),
                0,
                '`new`.`id` = 1',
                '%60new%60.%60id%60+%3D+1',
                array(
                    '`new`.`id`' => '= 1',
                ),
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                'l',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=update&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=insert&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'edit_row_anchor',
                '<span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span>',
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                '<td  class="center"><input type="checkbox" id="id_rows_to_delete0_left" name="rows_to_delete[0]" class="multi_checkbox" value="%60new%60.%60id%60+%3D+1"  /><input type="hidden" class="condition_array" value="{&quot;`new`.`id`&quot;:&quot;= 1&quot;}" />    </td>'
            )
        );
    }

    /**
     * Test for _getCheckboxAndLinks - case 3
     *
     * @param string $position          the position of the checkbox and links
     * @param string $del_url           delete url
     * @param array  $is_display        array with explicit indexes for all the
     *                                  display elements
     * @param string $row_no            row number
     * @param string $where_clause      where clause
     * @param string $where_clause_html url encoded where clause
     * @param array  $condition_array   array of conditions in the where clause
     * @param string $del_query         delete query
     * @param string $id_suffix         suffix for the id
     * @param string $edit_url          edit url
     * @param string $copy_url          copy url
     * @param string $class             css classes for the td elements
     * @param string $edit_str          text for the edit link
     * @param string $copy_str          text for the copy link
     * @param string $del_str           text for the delete link
     * @param string $js_conf           text for the JS confirmation
     * @param string $output            output of _getCheckboxAndLinks
     *
     * @dataProvider dataProviderForGetCheckboxAndLinksCase3
     */
    public function testGetCheckboxAndLinksCase3(
        $position, $del_url, $is_display, $row_no, $where_clause,
        $where_clause_html, $condition_array, $del_query, $id_suffix, $edit_url,
        $copy_url, $class, $edit_str, $copy_str, $del_str, $js_conf, $output
    ) {

        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getCheckboxAndLinks',
                array(
                    $position, $del_url, $is_display, $row_no, $where_clause,
                    $where_clause_html, $condition_array, $del_query,
                    $id_suffix, $edit_url, $copy_url, $class, $edit_str,
                    $copy_str, $del_str, $js_conf
                )
            )
        );
    }

    /**
     * Test for _mimeDefaultFunction
     */
    public function testMimeDefaultFunction()
    {
        $this->assertEquals(
            "A 'quote' is &lt;b&gt;bold&lt;/b&gt;",
            $this->_callPrivateFunction(
                '_mimeDefaultFunction',
                array("A 'quote' is <b>bold</b>")
            )
        );
    }

    /**
     * Data provider for testGetPlacedLinks
     *
     * @return array parameters and output
     */
    public function dataProviderForGetPlacedLinks()
    {
        return array(
            array(
                PMA_DisplayResults::POSITION_NONE,
                'sql.php?db=data&amp;table=new&amp;sql_query=DELETE+FROM+%60data%60.%60new%60+WHERE+%60new%60.%60id%60+%3D+1&amp;message_to_show=The+row+has+been+deleted&amp;goto=sql.php%3Fdb%3Ddata%26table%3Dnew%26sql_query%3DSELECT%2B%252A%2BFROM%2B%2560new%2560%26message_to_show%3DThe%2Brow%2Bhas%2Bbeen%2Bdeleted%26goto%3Dtbl_structure.php%26token%3Dae4c6d18375f446dfa068420c1f6a4e8&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                array(
                    'edit_lnk' => 'ur',
                    'del_lnk' => 'dr',
                    'sort_lnk' => '0',
                    'nav_bar' => '1',
                    'ins_row' => '1',
                    'bkm_form' => '1',
                    'text_btn' => '1',
                    'pview_lnk' => '1'
                ),
                0,
                '`new`.`id` = 1',
                '%60new%60.%60id%60+%3D+1',
                array(
                    '`new`.`id`' => '= 1',
                ),
                'DELETE FROM `data`.`new` WHERE `new`.`id` = 1',
                'l',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=update&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'tbl_change.php?db=data&amp;table=new&amp;where_clause=%60new%60.%60id%60+%3D+1&amp;clause_is_unique=1&amp;sql_query=SELECT+%2A+FROM+%60new%60&amp;goto=sql.php&amp;default_action=insert&amp;token=ae4c6d18375f446dfa068420c1f6a4e8',
                'edit_row_anchor',
                '<span class="nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit" /> Edit</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Copy" alt="Copy" class="icon ic_b_insrow" /> Copy</span>',
                '<span class="nowrap"><img src="themes/dot.gif" title="Delete" alt="Delete" class="icon ic_b_drop" /> Delete</span>',
                null,
                '<td  class="center"><input type="checkbox" id="id_rows_to_delete0_left" name="rows_to_delete[0]" class="multi_checkbox" value="%60new%60.%60id%60+%3D+1"  /><input type="hidden" class="condition_array" value="{&quot;`new`.`id`&quot;:&quot;= 1&quot;}" />    </td>'
            )
        );
    }

    /**
     * Test for _getPlacedLinks
     *
     * @param string  $dir               the direction of links should place
     * @param string  $del_url           the url for delete row
     * @param array   $is_display        which elements to display
     * @param integer $row_no            the index of current row
     * @param string  $where_clause      the where clause of the sql
     * @param string  $where_clause_html the html encoded where clause
     * @param array   $condition_array   array of keys (primary, unique, condition)
     * @param string  $del_query         the query for delete row
     * @param string  $dir_letter        the letter denoted the direction
     * @param string  $edit_url          the url for edit row
     * @param string  $copy_url          the url for copy row
     * @param string  $edit_anchor_class the class for html element for edit
     * @param string  $edit_str          the label for edit row
     * @param string  $copy_str          the label for copy row
     * @param string  $del_str           the label for delete row
     * @param string  $js_conf           text for the JS confirmation
     * @param string  $output            output of _getPlacedLinks
     *
     * @dataProvider dataProviderForGetPlacedLinks
     */
    public function testGetPlacedLinks(
        $dir, $del_url, $is_display, $row_no, $where_clause, $where_clause_html,
        $condition_array, $del_query, $dir_letter, $edit_url, $copy_url,
        $edit_anchor_class, $edit_str, $copy_str, $del_str, $js_conf, $output
    ) {
        $this->assertEquals(
            $output,
            $this->_callPrivateFunction(
                '_getPlacedLinks',
                array(
                    $dir, $del_url, $is_display, $row_no, $where_clause,
                    $where_clause_html, $condition_array, $del_query,
                    $dir_letter, $edit_url, $copy_url, $edit_anchor_class,
                    $edit_str, $copy_str, $del_str, $js_conf
                )
            )
        );
    }


}
