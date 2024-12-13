// グローバル変数
int score = 0;
int level = 1;
int lives = 3;
String input = "";
boolean gameOver = false;

// 数列関連の変数
int[] currentSequence;
int nextNumber;

void setup() {
    size(800, 600);
    textAlign(CENTER, CENTER);
    generateNewSequence();
}

void draw() {
    background(230);
    
    if (!gameOver) {
        // 数列の表示
        displaySequence();
        
        // 入力欄の表示
        displayInputBox();
        
        // スコアとライフの表示
        displayStats();
    } else {
        displayGameOver();
    }
}

void displaySequence() {
    textSize(32);
    fill(50);
    float x = 100;
    float y = height/2;
    
    for (int i = 0; i < currentSequence.length; i++) {
        text(str(currentSequence[i]), x + i*80, y);
    }
    text("?", x + currentSequence.length*80, y);
}

void keyPressed() {
    if (!gameOver) {
        if (keyCode == ENTER) {
            checkAnswer();
        } else if (keyCode == BACKSPACE) {
            if (input.length() > 0) {
                input = input.substring(0, input.length()-1);
            }
        } else if (key >= '0' && key <= '9') {
            input += key;
        }
    }
}

void checkAnswer() {
    int answer = int(input);
    if (answer == nextNumber) {
        score += 10 * level;
        generateNewSequence();
    } else {
        lives--;
        if (lives <= 0) {
            gameOver = true;
        }
    }
    input = "";
}

void generateNewSequence() {
    // レベルに応じた数列の生成
    switch(level) {
        case 1:
            generateArithmeticSequence();
            break;
        case 2:
            generateGeometricSequence();
            break;
        case 3:
            generateFibonacciSequence();
            break;
        // 他の数列パターン
    }
}