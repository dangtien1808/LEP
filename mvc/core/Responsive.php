<?php
class Responsive{
	
	/* Phương thức chuyển về trang lỗi */
	public function error($mode = 404, $message = []){
		$errors = [
			100 => "Yêu cầu đã được tiếp nhận",
			200	=> "Yêu cầu xử lý thành công",
			301 => "Yêu cầu cần được gửi sang địa chỉ khác",
			400	=> "Yêu cầu không hợp lệ",
			401	=> "Yêu cầu không được cấp quyền",
			403	=> "Yêu cầu bị cấm truy cập",
			404	=> "Yêu cầu không tồn tại, không tìm thấy",
			405	=> "Phương thức không hợp lệ",
			406	=> "Không được chấp nhận",
			410	=> "Yêu cầu không còn sử dụng được nữa",
			429	=> "Quá nhiều yêu cầu, hạn chế tiếp nhận yêu cầu",
			500	=> "Lỗi máy chủ nội bộ",
			503	=> "Dịch vụ không hoạt động",
			422 => ""
		];
		if(!isset($errors[$mode])){
			$mode = 404;
		}
		$responsive = [
			"status" => false,
			"code" => $mode,
			"errors" => [
				'messages' => $errors[$mode]
			] 
		];
		if($message){
			$responsive['errors']['messages'] = $message;
		}
		return $responsive;
	}

	// Phương thức trả về kết quả thành công
	public function success($data){
		$responsive =  [
            "status" => true,
            "code" => 200,
            "results" => $data
		];
		return  $responsive;
	}
}
?>