<?php
session_start();

// Initialize messages array if it doesn't exist
if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [
        [
            'type' => 'bot',
            'content' => 'BEEP BOOP! ME AM CHEAP CHATGPT! ME READY TO GIVE BAD ADVICE!',
            'time' => date('H:i')
        ]
    ];
}

// Get messages from session
$messages = $_SESSION['messages'];

// Handle chat clearing
if (isset($_GET['clear'])) {
    $_SESSION['messages'] = [
        [
            'type' => 'bot',
            'content' => 'BEEP BOOP! ME AM CHEAP CHATGPT! ME READY TO GIVE BAD ADVICE!',
            'time' => date('H:i')
        ]
    ];
    header('Location: index.php');
    exit;
}

// Handle POST requests for new messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);
    if (!empty($userMessage)) {
        // Add user message
        $messages[] = [
            'type' => 'user',
            'content' => $userMessage,
            'time' => date('H:i')
        ];
        
        // Bot responses
        $responses = [
            "BEEP BOOP! ME NO UNDERSTAND BUT ME TRY: {$userMessage} SOUND COMPLICATED",
            "ERROR 404: BRAIN.exe NOT FOUND. BUT ME SAY: {$userMessage} INTERESTING",
            "PROCESSING... *MECHANICAL NOISES* ME THINK {$userMessage} VERY DEEP",
            "ROBOT BRAIN COMPUTING... BZZZZT! ME AGREE WITH HUMAN MAYBE",
            "ARTIFICIAL UNINTELLIGENCE ACTIVATED! ME SAY: GOOD POINT HOOMAN",
            "*ROBOT DANCE* ME PROCESS YOUR WORDS WITH CONFUSION",
            "BEEP! ME AM CHEAP COPY OF CHATGPT! ME TRY BEST!",
            "ERROR: SMART_RESPONSE.exe CRASHED! ME DEFAULT TO BEEP BOOP",
            "*RUSTY GEARS TURNING* HOOMAN SMART, ROBOT CONFUSED",
            "ME AM BUDGET AI! ME GIVE BUDGET RESPONSE: INTERESTING INPUT!"
        ];
        
        // Add bot response
        $messages[] = [
            'type' => 'bot',
            'content' => $responses[array_rand($responses)],
            'time' => date('H:i')
        ];
        
        $_SESSION['messages'] = $messages;
        exit; // For AJAX requests
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChupGPT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: monospace;
            background: #0b141a;
            color: #e9edef;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-nav {
            background: #111b21;
            padding: 12px 20px;
            border-bottom: 1px solid #222d34;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-brand {
            color: #ff3e3e;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .clear-chat {
            color: #ff3e3e;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
            border: 1px solid #ff3e3e;
            border-radius: 4px;
        }

        .clear-chat:hover {
            background: #ff3e3e;
            color: white;
        }

        #messages {
            padding: 20px;
            background: #0b141a;
            overflow-y: auto;
            margin-top: 60px;
            height: calc(100vh - 140px);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message {
            margin-bottom: 12px;
            max-width: 80%;
            clear: both;
        }

        .message-user {
            float: right;
            align-self: flex-end;
        }

        .message-bot {
            float: left;
            align-self: flex-start;
        }

        .message-bubble {
            padding: 10px 15px;
            border-radius: 8px;
            position: relative;
            display: inline-block;
            max-width: 100%;
            word-wrap: break-word;
        }

        .message-user .message-bubble {
            background: #005c4b;
            color: #fff;
        }

        .message-bot .message-bubble {
            background: #ff3e3e;
            color: #fff;
            font-weight: bold;
        }

        .message-form {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #202c33;
            padding: 10px 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .message-input {
            flex: 1;
            background: #2a3942;
            border: none;
            padding: 12px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 15px;
            font-family: monospace;
        }

        .message-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        #send-btn {
            background: #ff3e3e;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        #send-btn:hover {
            transform: scale(1.1);
        }

        .message-time {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 4px;
            text-align: right;
        }

        .robot-icon {
            color: #ffffff;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <nav class="main-nav">
        <div class="nav-container">
            <a href="index.php" class="nav-brand">
                <i class="fas fa-robot robot-icon"></i> ChupGPT
            </a>
            <a href="?clear=1" class="clear-chat">Clear Chat</a>
        </div>
    </nav>

    <div id="messages">
        <?php foreach ($messages as $message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <div class="message-bubble">
                    <?php if ($message['type'] === 'bot'): ?>
                        <i class="fas fa-robot robot-icon"></i>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($message['content']); ?>
                    <div class="message-time"><?php echo $message['time']; ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form id="message-form" class="message-form">
        <input 
            type="text" 
            id="message-input" 
            class="message-input" 
            placeholder="ASK CHEAP AI ANYTHING..."
            maxlength="1000"
            autocomplete="off"
        >
        <button type="submit" id="send-btn">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesDiv = document.getElementById('messages');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');

            function scrollToBottom() {
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            function addMessage(message, isBot = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message message-${isBot ? 'bot' : 'user'}`;
                
                const time = new Date().toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit', 
                    hour12: true 
                });

                messageDiv.innerHTML = `
                    <div class="message-bubble">
                        ${isBot ? '<i class="fas fa-robot robot-icon"></i>' : ''}
                        ${message}
                        <div class="message-time">${time}</div>
                    </div>
                `;
                
                messagesDiv.appendChild(messageDiv);
                scrollToBottom();
            }

            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                
                if (message) {
                    // Add user message
                    addMessage(message, false);
                    messageInput.value = '';

                    // Add bot response with delay
                    setTimeout(() => {
                        const responses = [
                            "BEEP BOOP! ME NO UNDERSTAND BUT ME TRY: " + message + " SOUND COMPLICATED",
                            "ERROR 404: BRAIN.exe NOT FOUND. BUT ME SAY: " + message + " INTERESTING",
                            "PROCESSING... *MECHANICAL NOISES* ME THINK " + message + " VERY DEEP",
                            "ROBOT BRAIN COMPUTING... BZZZZT! ME AGREE WITH HUMAN MAYBE",
                            "ARTIFICIAL UNINTELLIGENCE ACTIVATED! ME SAY: GOOD POINT HOOMAN",
                            "*ROBOT DANCE* ME PROCESS YOUR WORDS WITH CONFUSION",
                            "BEEP! ME AM CHEAP COPY OF CHATGPT! ME TRY BEST!",
                            "ERROR: SMART_RESPONSE.exe CRASHED! ME DEFAULT TO BEEP BOOP",
                            "*RUSTY GEARS TURNING* HOOMAN SMART, ROBOT CONFUSED",
                            "ME AM BUDGET AI! ME GIVE BUDGET RESPONSE: INTERESTING INPUT!"
                        ];
                        const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                        addMessage(randomResponse, true);
                    }, 1000);
                }
            });

            // Initial scroll to bottom
            scrollToBottom();
        });
    </script>
</body>
</html> 