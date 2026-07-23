<?php
/**
 * 创建测试用户脚本
 * 访问方式：http://your-domain.com/create_test_users.php
 * 执行后会输出SQL语句，也会尝试直接写入数据库
 */

require __DIR__ . '/../vendor/autoload.php';

// 初始化应用
$app = new \think\App();
$app->initialize();

// 测试用户数据
$testUsers = [
    [
        'user_name'  => 'admin',
        'user_nick'  => '管理员',
        'user_mobile' => '13800138001',
        'user_email' => 'admin@example.com',
        'password'   => '123456',
        'user_status' => 1,
        'real_auth_status' => 2,
    ],
    [
        'user_name'  => 'testuser',
        'user_nick'  => '测试用户',
        'user_mobile' => '13800138002',
        'user_email' => 'test@example.com',
        'password'   => '123456',
        'user_status' => 1,
        'real_auth_status' => 1,
    ],
    [
        'user_name'  => 'guest',
        'user_nick'  => '访客',
        'user_mobile' => '13800138003',
        'user_email' => 'guest@example.com',
        'password'   => '123456',
        'user_status' => 1,
        'real_auth_status' => 0,
    ],
];

echo '<h1>创建测试用户</h1>';
echo '<pre>';

try {
    foreach ($testUsers as $userData) {
        // 生成盐值
        $salt = md5(uniqid((string)mt_rand(), true));
        // 生成密码哈希
        $passwordHash = password_hash($userData['password'] . $salt, PASSWORD_DEFAULT);
        
        // 生成SQL语句
        $sql = sprintf(
            "INSERT INTO `us_account` (`user_name`, `user_nick`, `user_mobile`, `user_email`, `password_hash`, `password_salt`, `user_status`, `real_auth_status`, `created_at`, `updated_at`)
             VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %d, %d, NOW(), NOW());",
            addslashes($userData['user_name']),
            addslashes($userData['user_nick']),
            addslashes($userData['user_mobile']),
            addslashes($userData['user_email']),
            addslashes($passwordHash),
            addslashes($salt),
            $userData['user_status'],
            $userData['real_auth_status']
        );
        
        echo "=== 用户: {$userData['user_name']} ===\n";
        echo "密码: {$userData['password']}\n";
        echo "SQL: {$sql}\n\n";
        
        // 尝试直接执行
        try {
            $result = \think\facade\Db::execute($sql);
            if ($result) {
                echo "✓ 成功插入用户: {$userData['user_name']}\n\n";
            } else {
                echo "✗ 插入失败: {$userData['user_name']}\n\n";
            }
        } catch (\Exception $e) {
            echo "✗ 执行失败: {$e->getMessage()}\n\n";
        }
    }
    
    echo "\n=== 测试用户列表 ===\n";
    echo "用户名\t\t密码\t\t手机号\t\t邮箱\n";
    echo "----------------------------------------------\n";
    foreach ($testUsers as $user) {
        echo "{$user['user_name']}\t\t{$user['password']}\t\t{$user['user_mobile']}\t\t{$user['user_email']}\n";
    }
    
} catch (\Exception $e) {
    echo "错误: {$e->getMessage()}\n";
    echo $e->getTraceAsString();
}

echo '</pre>';
