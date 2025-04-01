<?php
require('../admin/database/db_config.php');
require('../admin/shares/essentials.php');
adminLogin();


if(isset($_POST['get_user']))
{
    $res = selectAll('user_cred');
    $i=1;
    $path = USERS_IMG_PATH;

    $data = "";

    white($row = mysqli_fetch_assoc($res))
    {
        $del_btn ="<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none'>
        <i class='bi bi-check-lg'></i></span>
        </button>";

        $verified = "<span class='badge bg-warning '><i class='bi bi-x-circle'></i></span>";
       

        if($row['is_verified'])
        {
            $verified = "<span class='badge bg-success '><i class='bi bi-check-lg'></i></span>";
            $del_btn = "";
        }

        $status = "<button onclick='toggle_status($row[id],o)' class ='btn btn-dark btn-sm shadow-none'>
        active
        </button>";

        if(!$row['status'])
        {
            $status = "<button onclick='toggle_status($row[id],1)' class ='btn btn-dark btn-sm shadow-none'>
            inactive
            </button>";
        }

        $date = date("d-m-Y",strtotime($row['datentime']));

        $data.="
             <tr>
                <td>$i</td>
                <td>
                <img src='$path$row[profile]' width='55px'>
                <br>
                $row [name]
                </td>
                <td>$row [email]</td>
                <td>$row [phonenum]</td>
                <td>$row [address] | $row[pincode] </td>
                <td>$row [dob]</td>
                <td>$verified</td>
                <td>$status</td>
                 <td>$date</td>
                <td>$del_btn</td>

            </tr>
        ";
        $i++;
    
    }
    echo $data;

    if(isset($_POST['remove_room']))
    {
        $frm_data = filteration($_POST);

        $res1 = select("SELECT * FROM `room_images` WHERE `room_id`=?", [$frm_data['room_id']], 'i');

        while($row = mysqli_fetch_assoc($res1)){
            deleteImage($row['image'], ROOMS_FOLDER);
        }

        $res2 = delete("DELETE FROM `room_images` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
        $res3 = delete("DELETE FROM `room_features` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
        $res4 = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
        $res5 = update("UPDATE `rooms` SET `removed`=? WHERE `id`=?", [1, $frm_data['room_id']], 'ii');

        if($res2 || $res3 || $res4 || $res5)
        {
            echo 1;
        } else 
        {
            echo 0;
        }
    }

    if(isset($_POST['remove_user']))
    {  
    $frm_data = filteration($_POST);

    $res1 = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?", [1,$frm_data['user_id']], 'i');

    if($res){
        echo 1;
    } else {
        echo 0;
    }
    }

    if (isset($_POST['search_user'])) {
        // Lọc dữ liệu đầu vào để tránh SQL Injection
        $frm_data = filtration($_POST);
        
        // Câu truy vấn tìm kiếm người dùng theo tên
        $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";
        
        // Thực thi truy vấn với tham số đã lọc
        $res = select($query, ["%{$frm_data['name']}%"], 's');
        
        // Định nghĩa đường dẫn ảnh người dùng
        $path = USERS_IMG_PATH;
        $data = "";
        
        // Duyệt qua từng kết quả trả về
        while ($row = mysqli_fetch_assoc($res)) {
            // Nút xóa người dùng
            $del_btn = "<button type='button' onclick='remove_user({$row['id']})' class='btn btn-danger shadow-none'>
                            <i class='bi bi-trash'></i>
                        </button>";
            
            // Hiển thị trạng thái xác minh
            $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";
            
            // Thêm thông tin người dùng vào chuỗi dữ liệu
            $data .= "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td><img src='{$path}{$row['image']}' width='50'></td>
                        <td>{$verified}</td>
                        <td>{$del_btn}</td>
                     </tr>";
        }
        
        echo $data; // Trả về dữ liệu dưới dạng HTML
    }
    

}


?> 