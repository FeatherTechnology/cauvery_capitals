<?php
include '../ajaxconfig.php';
class branchProcess
{
    private $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getBranchList($user_id)
    {
        $response = array();
        $qry = $this->connect->query("SELECT bc.branch_id, bc.branch_name FROM branch_creation bc JOIN user u ON FIND_IN_SET(bc.branch_id, u.branch_id) WHERE u.user_id = '$user_id'");
        while ($row = $qry->fetch()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getAreaList($branch_id, $user_id)
    {
        $area_list = array();

        if ($branch_id == 0) {
            $branch_list = $this->getBranchList($user_id);
            foreach ($branch_list as $branch) {
                $qry = $this->connect->query("SELECT agma.area_id FROM area_group_mapping agm  join area_group_mapping_area agma on agm.map_id = agma.group_map_id WHERE agm.branch_id = '" . $branch['branch_id'] . "'");
                while ($row = $qry->fetch()) {
                    $area_list[] = $row['area_id'];
                }
            }
        } else {
            $qry = $this->connect->query("SELECT agma.area_id FROM area_group_mapping agm  join area_group_mapping_area agma on agm.map_id = agma.group_map_id WHERE agm.branch_id = $branch_id");
            while ($row = $qry->fetch()) {
                $area_list[] = $row['area_id'];
            }
        }

        $area_ids = array();
        foreach ($area_list as $subarray) {
            $area_ids = array_merge($area_ids, explode(',', $subarray));
        }
        $area_list = implode(',', $area_ids);

        return !empty($area_list) ? $area_list : 'Error';
    }
}
