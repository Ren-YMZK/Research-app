// 因数分解ゲーム

// グーム状態
boolean gameStarted = false;
String currentProblem = "";
String inputAnswer = "";
boolean isGameOver = false;
boolean isWinner = false;

// デザイン設定
color backgroundColor = color(240, 240, 240);
color textColor = color(50, 50, 50);
color boxColor = color(255, 255, 255);
color boxBorderColor = color(200, 200, 200);
color inputTextColor = color(50, 50, 50);
color guideTextColor = color(100, 100, 100);

// レイアウト設定
int problemY;
int inputBoxY;
int guideTextY;
int boxWidth;
int boxHeight = 50;

void setup() {
    size(800, 600);
    textAlign(CENTER, CENTER);
    frameRate(60);
    
    // フォント設定
    PFont font = createFont("Arial", 32);
    textFont(font);
    
    // レイアウト計算
    problemY = height/3;
    inputBoxY = height/2;
    guideTextY = height/2 + 80;
    boxWidth = width/2;
}

void draw() {
    background(backgroundColor);
    
    if (!gameStarted) {
        showWaitingScreen();
    } else if (isGameOver) {
        showGameOverScreen();
    } else {
        showGameScreen();
    }
}

void showWaitingScreen() {
    fill(textColor);
    textSize(32);
    text("対戦相手を待っています...", width/2, height/2);
}

void showGameScreen() {
    // 問題表示
    fill(textColor);
    textSize(48);
    text(currentProblem, width/2, problemY);
    
    // 入力ボックス
    drawInputBox();
    
    // ガイドテキスト
    fill(guideTextColor);
    textSize(20);
    text("答えを入力してEnterキーを押してください", width/2, guideTextY);
}

void drawInputBox() {
    // ボックスの背景
    rectMode(CENTER);
    fill(boxColor);
    stroke(boxBorderColor);
    strokeWeight(2);
    rect(width/2, inputBoxY, boxWidth, boxHeight, 5);
    
    // 入力テキスト
    fill(inputTextColor);
    textSize(32);
    text(inputAnswer, width/2, inputBoxY);
    
    // カーソル点滅効果
    if (frameCount % 60 < 30 && !isGameOver) {
        float textWidth = textWidth(inputAnswer);
        float cursorX = width/2 + textWidth/2 + 10;
        stroke(inputTextColor);
        line(cursorX, inputBoxY - 20, cursorX, inputBoxY + 20);
    }
}

void showGameOverScreen() {
    fill(textColor);
    textSize(48);
    if (isWinner) {
        text("あなたの勝ち！", width/2, height/2 - 40);
    } else {
        text("あなたの負け...", width/2, height/2 - 40);
    }
    
    textSize(24);
    text("Spaceキーを押してタイトルに戻る", width/2, height/2 + 40);
}

void keyPressed() {
    if (gameStarted && !isGameOver) {
        if (key == ENTER || key == RETURN) {
            if (inputAnswer.trim().length() > 0) {
                submitAnswer();
            }
        } else if (key == BACKSPACE) {
            if (inputAnswer.length() > 0) {
                inputAnswer = inputAnswer.substring(0, inputAnswer.length() - 1);
            }
        } else if (key != CODED) {
            // 入力可能な文字を制限
            String validChars = "0123456789x+-^() ";
            if (validChars.indexOf(str(key)) != -1) {
                inputAnswer += str(key);
            }
        }
    } else if (isGameOver && key == ' ') {
        window.location.href = '/';
    }
}

void submitAnswer() {
    if (window != null) {
        window.submitAnswer(inputAnswer.trim());
    }
    inputAnswer = "";
}

// 外部からの呼び出し用メソッド
void startGame(String problem) {
    gameStarted = true;
    currentProblem = problem;
    inputAnswer = "";
    isGameOver = false;
}

void setProblem(String problem) {
    currentProblem = problem;
    inputAnswer = "";
}

void showGameOver(boolean winner) {
    isGameOver = true;
    isWinner = winner;
}

// Processing.jsの準備完了時に呼び出される
void bindJavascript(JavaScript js) {
    if (window != null) {
        window.processingReady(this);
    }
}

//実行するときは以下のコマンドを実行
//& "C:\processing-4.3\processing-java.exe" --sketch="C:\Processing\iv" --run