// 因数分解ゲーム

// グローバル変数
int score = 0;
int level = 1;
float speed = 1;
Problem currentProblem;
PFont japaneseFont;
boolean gameStarted = false;
boolean levelSelected = false;
int instructionStartTime;
int selectedLevel = 1;
int maxLevel = 3;
int selectedSpeed = 3;
int maxSpeed = 5;
boolean gameOver = false;
boolean isMouseOverExitButton = false;
boolean isPaused = false;
boolean isMouseOverPauseButton = false;
boolean showOpening = true;

void setup() {
  size(800, 500);
  textAlign(CENTER, CENTER);
  japaneseFont = createFont("MS Gothic", 24);
  textFont(japaneseFont);
}

void draw() {
  if (showOpening) {
    showOpeningScreen();
  } else if (!levelSelected) {
    showLevelSelection();
  } else if (!gameStarted) {
    showInstructions();
  } else if (!gameOver) {
    if (!isPaused) {
      background(240);
      currentProblem.display();
      currentProblem.move();
      drawAnswerBox();
      drawScore();
    }
    drawPauseButton();
    if (isPaused) {
      showPauseMenu();
    }
  } else {
    showGameOver();
  }
}

void showInstructions() {
  background(230, 230, 250);  // 薄い紫色の背景
  fill(50, 50, 100);  // 濃い青紫色の文字
  textSize(18);
  textAlign(LEFT, TOP);
  text("操作方法：\n\n" +
       "1. 落ちてくる二次方程式を\n   因数分解します。\n\n" +
       "2. 因数の数字をスペースで\n   区切って入力します。\n" +
       "   負の数はマイナス記号を\n   つけて入力します。\n" +
       "   例：x^2 - x - 6 → -3 2\n\n" +
       "3. Enterキーを押して\n   回答を確定します。", 20, 20);
  
  // カウントダウンの代わりにEnterキーの案内を表示
  textAlign(CENTER, BOTTOM);
  textSize(24);
  fill(100, 100, 200);  // 明るい青紫色
  text("Enterキーを押してゲームを開始", width/2, height - 20);
}

void showLevelSelection() {
  background(230, 230, 250);
  fill(50, 50, 100);
  textSize(28);
  text("難易度を選んでください", width/2, 60);
  
  drawLevelButtons();
  drawSpeedSlider();
  drawInstructions();
  drawExitButton();
}

void drawLevelButtons() {
  rectMode(CENTER);
  textAlign(CENTER, CENTER);
  
  for (int i = 1; i <= maxLevel; i++) {
    int buttonY = 140 + 70 * (i-1);
    int buttonWidth = 200;
    int buttonHeight = 60;
    
    if (i == selectedLevel) {
      fill(100, 100, 200);  // 選択中のレベルの色
    } else {
      fill(80, 80, 150);    // 非選択のレベルの色
    }
    rect(width/2, buttonY, buttonWidth, buttonHeight, 15);  // 角を丸くする
    
    fill(255);
    textSize(24);
    text("レベル " + i, width/2, buttonY);
  }
  
  rectMode(CORNER);  // 元の設定に戻す
}

void drawSpeedSlider() {
  fill(50, 50, 100);
  textSize(20);
  text("スピード: " + selectedSpeed, width/2, height - 160);
  
  // スライドバーの背景
  stroke(100);
  strokeWeight(2);
  line(width/4, height - 130, 3*width/4, height - 130);
  
  // スライドバーのつまみ
  float thumbX = map(selectedSpeed, 1, maxSpeed, width/4, 3*width/4);
  fill(100, 100, 200);
  noStroke();
  ellipse(thumbX, height - 130, 20, 20);
  
  strokeWeight(1);  // 元の設定に戻す
}

void drawInstructions() {
  fill(50, 50, 100);
  textSize(18);
  text("↑↓キーでレベル選択", width/2, height - 100);
  text("←→キーでスピード調整", width/2, height - 75);
  text("Enterで決定", width/2, height - 50);
}

void drawAnswerBox() {
  // 回答欄の背景
  fill(255);
  stroke(0);
  rectMode(CORNER);
  rect(10, height - 60, width - 20, 50);
  
  
  // 回答欄のテキスト
  fill(0);
  textAlign(LEFT, CENTER);
  textSize(24);
  String displayText =" ";
  
  if(currentProblem.input != " ") {
  String input = currentProblem.input.trim(); // 前後の空白を削除

  String[] numbers = input.split("\\s+"); // 1つ以上の空白で分割
    for (int i = 0; i < numbers.length; i++) {
      if( numbers[i] >= 0 ){
        displayText += "(x+" + numbers[i] +")" ;
      }
      else{
        displayText += "(x" + numbers[i] +")" ;
      }
    }
    text("回答:" + displayText, 20, height - 35);
  }

 /*if (currentProblem != null) {

  
    String displayText = "回答: 数字1 数字2";
    // String displayText = currentProblem.input.isEmpty() ? "回答: 数字1 数字2" : "回答: (x+" + currentProblem.input.replace(" ", ")(x+") + ")";
    text(displayText, 20, height - 35);
  } else {
    text("a回答: 数字1 数字2", 20, height - 35);
  }*/
  
  textAlign(CENTER, CENTER);
}

void keyPressed() {
    if (showOpening) {
        if (keyCode == ENTER) {
            showOpening = false;
        }
    } else if (!levelSelected) {
        if (keyCode == UP) {
            selectedLevel = max(1, selectedLevel - 1);
        } else if (keyCode == DOWN) {
            selectedLevel = min(maxLevel, selectedLevel + 1);
        } else if (keyCode == LEFT) {
            selectedSpeed = max(1, selectedSpeed - 1);
        } else if (keyCode == RIGHT) {
            selectedSpeed = min(maxSpeed, selectedSpeed + 1);
        } else if (keyCode == ENTER) {
            levelSelected = true;
            instructionStartTime = millis();
            speed = selectedSpeed * 0.5;
        }
    } else if (!gameStarted) {
        if (keyCode == ENTER) {  // 説明画面でEnterキーが押されたら
            gameStarted = true;
            newProblem();
        }
    } else if (gameOver) {
        if (keyCode == ENTER) {
            resetGame();
        } else if (key == ' ') {
            returnToLevelSelection();
        }
    } else {
        if (keyCode == ENTER) {
            checkAnswer();
        } else if (keyCode == BACKSPACE) {
            if (currentProblem.input.length() > 0) {
                currentProblem.input = currentProblem.input.substring(0, currentProblem.input.length() - 1);
            }
        } else {
            if (key >= '0' && key <= '9') {
                currentProblem.input += str(key - '0');
            } else if (key == ' ') {
                currentProblem.input += " ";
            } else if (key == '-') {
                currentProblem.input += "-";
            }
        }
    }
}

void newProblem() {
  currentProblem = new Problem();
}

void checkAnswer() {
    String input = currentProblem.input.trim(); // 前後の空白を削除
    String[] numbers = input.split("\\s+"); // 1つ以上の空白で分割

    if ((numbers[0] == currentProblem.a && numbers[1] == currentProblem.b)|| (numbers[0] == currentProblem.b && numbers[1] == currentProblem.a)){
        score++;
        if (score % 5 == 0) {
            level++;
            speed += 0.5;
        }
        newProblem();
    } else {
        currentProblem.input = " ";
    }
}


class Problem {
  int a, b;
  float y;
  String input = "";
  String question;
  
  Problem() {
    input = "";  // 入力を確実に空文字で初期化
    y = 0;
    
    // レベルに応じて因数の範囲を調整
    int minFactor, maxFactor;
    switch (selectedLevel) {
      case 1:
        minFactor = 1;
        maxFactor = 5;
        break;
      case 2:
        minFactor = -5;
        maxFactor = 5;
        break;
      case 3:
        minFactor = -9;
        maxFactor = 9;
        break;
      default:
        minFactor = 1;
        maxFactor = 5;
    }
    
    // 因数を生成
    a = int(random(minFactor, maxFactor + 1));
    b = int(random(minFactor, maxFactor + 1));
    
    // レベル1では0を避る、他のレベルでは0を因数に含めない
    if (selectedLevel == 1) {
      while (a == 0 || b == 0) {
        a = int(random(minFactor, maxFactor + 1));
        b = int(random(minFactor, maxFactor + 1));
      }
    } else {
      while (a == 0) a = int(random(minFactor, maxFactor + 1));
      while (b == 0) b = int(random(minFactor, maxFactor + 1));
    }
    
    // 二次方程式の係数を計算
    int sum = a + b;
    int product = a * b;
    
    // 二次方程式を生成
    question = "x^2";
    if (sum != 0) {
      question += (sum > 0) ? " + " + sum + "x" : " - " + abs(sum) + "x";
    }
    if (product != 0) {
      question += (product > 0) ? " + " + product : " - " + abs(product);
    }
  
  }
  
  void display() {
    fill(0);
    textAlign(CENTER , TOP);
    textSize(24);
    text(question + "の因数分解は？", width/2, y);
  }
  
  void move() {
    y += speed;
    
    // 問題が画面下部に到達したらゲームオーバー
    if (y > height) {
      gameOver = true;
    }
  }
  
  boolean checkAnswer() {
    println("Checking answer in Problem class"); // デバッグ出力開始
    
    // 入力が空の場合はfalseを返す
    if (input.trim().isEmpty()) {
      println("Input is empty");
      return false;
    }
    
    String[] numbers = input.trim().split("\\s+");
    println("Split input into: " + java.util.Arrays.toString(numbers)); // 分割結果を確認
    
    if (numbers.length != 2) {
      println("Wrong number of inputs: " + numbers.length);
      return false;
    }
    
    try {
      int num1 = Integer.parseInt(numbers[0]);
      int num2 = Integer.parseInt(numbers[1]);
      
      println("Parsed numbers: " + num1 + ", " + num2);
      println("Expected numbers: " + a + ", " + b);
      
      boolean correct = (num1 == a && num2 == b) || (num1 == b && num2 == a);
      println("Answer is: " + (correct ? "correct" : "incorrect"));
      return correct;
    } catch (NumberFormatException e) {
      println("Failed to parse numbers: " + e.getMessage());
      return false;
    }
  }
}

void resetGame() {
  gameOver = false;
  score = 0;
  speed = selectedSpeed * 0.5;  // スピードをリセット
  newProblem();
}

void returnToLevelSelection() {
  gameOver = false;
  gameStarted = false;
  levelSelected = false;
  score = 0;
}

void showGameOver() {
  background(0);
  fill(255, 0, 0);
  textSize(48);
  text("Game Over", width/2, height/2 - 80);
  
  fill(255);
  textSize(24);
  text("最終スコア: " + score, width/2, height/2);
  
  // スコアをサーバーに送信
  try {
    if (window != null) {
      window.saveScore(score, selectedLevel, selectedSpeed);
    } else {
      println("window object is not available");
    }
  } catch (Exception e) {
    println("スコアの保存に失敗しました: " + e.toString());
  }
  
  textSize(18);
  text("Enterキーを押して再開", width/2, height/2 + 60);
  text("Spaceキーを押してレベル選択に戻る", width/2, height/2 + 90);
}

void drawExitButton() {
  rectMode(CENTER);
  textAlign(CENTER, CENTER);
  
  int buttonWidth = 120;
  int buttonHeight = 40;
  int buttonY = height - 20;
  
  if (isMouseOverExitButton) {
    fill(220, 70, 70);
  } else {
    fill(180, 60, 60);
  }
  rect(width/2, buttonY, buttonWidth, buttonHeight, 10);
  
  fill(255);
  textSize(18);
  text("終了", width/2, buttonY);
  
  rectMode(CORNER);  // 元の設定に戻す
}

void mousePressed() {
  if (!levelSelected && isMouseOverExitButton) {
    exit();
  }
  if (gameStarted && !gameOver) {
    if (mouseX > width - 60 && mouseX < width - 10 && mouseY > 10 && mouseY < 40) {
      isPaused = !isPaused;
      if (!isPaused) {
        // ゲーム再開時の処理（必要に応じて）
      }
    }
  }
}

void mouseMoved() {
  if (!levelSelected) {
    int buttonWidth = 120;
    int buttonHeight = 40;
    int buttonY = height - 20;
    isMouseOverExitButton = (mouseX > width/2 - buttonWidth/2 && mouseX < width/2 + buttonWidth/2 && 
                             mouseY > buttonY - buttonHeight/2 && mouseY < buttonY + buttonHeight/2);
  }
  if (gameStarted && !gameOver) {
    isMouseOverPauseButton = (mouseX > width - 60 && mouseX < width - 10 && mouseY > 10 && mouseY < 40);
  }
}

void drawPauseButton() {
  fill(isMouseOverPauseButton ? color(100, 100, 200) : color(50, 50, 100));
  rect(width - 60, 10, 50, 30, 5);
  fill(255);
  textAlign(CENTER, CENTER);
  textSize(16);
  text(isPaused ? "再開" : "停止", width - 35, 25);
}

void showPauseMenu() {
  fill(0, 150);
  rect(0, 0, width, height);
  fill(255);
  textAlign(CENTER, CENTER);
  textSize(32);
  text("一時停止中", width/2, height/2 - 40);
  textSize(20);
  text("クリックして再開", width/2, height/2 + 20);
}

void drawScore() {
  fill(50, 50, 100);
  textAlign(LEFT, TOP);
  textSize(20);
  text("スコア: " + score, 10, 10);
}

void showOpeningScreen() {
  background(230, 230, 250);
  
  fill(50, 50, 100);
  textSize(48);
  text("因数分解ゲーム", width/2, height/3);
  
  textSize(24);
  text("作者: Ren Yamazaki", width/2, height/2);
  
  textSize(18);
  text("Enterキーを押してスタート", width/2, height * 2/3);
}


//実行するときは以下のコマンドを実行
//& "C:\processing-4.3\processing-java.exe" --sketch="C:\Processing\iv" --run