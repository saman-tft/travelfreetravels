<?php
$module = $customer_details['module']; 
//debug($customer_details);exit;
?>
<style>table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;    
}</style>
<table>
    <tbody><tr>
            <th>Title </th>
            <th>First Name</th>
            <th>Last Name</th>
          
       
            
          
        </tr>
        
    <?php
    // debug($customer_details);exit;
    // debug($customer_details['data']['booking_customer_details']);exit;
    foreach ($customer_details['data']['booking_customer_details'] as $key => $value) {
                extract($value);
                if($module == 'flight'){
                    $pax_type = $passenger_type;
                    $phone = $customer_details['data']['booking_details'][0]['phone'];
                }
                else if($module == 'hotel'){
                    $pax_type = $pax_type;
                    if($title == 'Mr' || $title == 'Mstr'){
                        $gender = 'Male';
                    }
                    else if($title == 'Ms'){
                        $gender = 'FeMale';
                    }
                   
                }
                else if($module == 'bus'){
                    $name = explode(' ', $name);
                    $first_name = $name[0];
                    $last_name = $name[1];
                    $phone = $customer_details['data']['booking_details'][0]['phone_number'];
                    $date_of_birth = $age;
                    if($gender == 'Male'){
                        $title = 'Mr';
                    }
                    else if($gender == 'FeMale'){
                        $title = 'Mrs';
                    }
                   
                }
                 if($title=="")
                    {
                        $title="Mr";
                    }
                $table_data .= '
							  <tr>
							    <td>'.$title.'</td>
							    <td>'.$first_name.'</td>
							    <td>'.$last_name.'</td>
                                ';
                              
							    
                                
							  $table_data .= '</tr>';
			}
                        echo $table_data;
                        ?>
    </tbody></table>