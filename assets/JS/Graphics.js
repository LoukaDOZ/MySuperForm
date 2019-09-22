function newGraphic(questionId,answersCount,answers,results){

  var answersArray = [];
  for(var i = 1, j = 0; j < answersCount; i++){

    if(answers[i]){

      answersArray[j] = answers[i];
      j++;
    }
  }

  var biggerCount = 0;
  for(i = 0; i < results.length; i++){

    if(results[i]['question_id'] == questionId && results[i]['count'] > biggerCount){

      biggerCount = results[i]['count'];
    }
  }

  var element = document.getElementById('canvas' + questionId);
  var canvas = element.getContext('2d');
  element.width = window.innerWidth * 0.60;
  var canvasWidth = element.width;

  var barSpacing = 40;
  var positionX = 10;
  var positionY = barSpacing;
  var barHeight = 20;
  var barWidth = canvasWidth - 2 * positionX;
  var colors = ['255,0,0','255,160,0','0,255,0','0,200,255']

  element.height = answersCount * barHeight + answersCount * barSpacing + positionX;
  var canvasHeight = element.height;

  canvas.beginPath();
  canvas.rect(0,0,canvasWidth,canvasHeight);
  canvas.fillStyle = "white";
  canvas.fill();
  canvas.closePath();

  var barWidthPercent
  for(i = 0, j = 0; i < answersArray.length; i++){

      canvas.beginPath();
      canvas.fillStyle = "black";
      canvas.fill();
      canvas.font = (barSpacing * 0.40) + 'px sans-serif';
      if(answersArray[i].length > canvasWidth / 10){

        answersArray[i] = answersArray[i].substr(0,canvasWidth / 10) + '...';
      }

      canvas.fillText(answersArray[i],positionX,positionY - 8);

      for(var k = 0, haveResult = -1; k < results.length; k++){

        if(results[k]['result'] == answersArray[i]){

          haveResult = k;
        }
      }

      if(haveResult != -1){

        canvas.fillText(results[haveResult]['count'],canvasWidth - results[haveResult]['count'].toString().length * 12,positionY - 8);
        barWidthPercent = results[haveResult]['count']/biggerCount;
        canvas.closePath();
      }else{

        canvas.fillText("0",canvasWidth - 12,positionY - 8);
        barWidthPercent = 0;
        canvas.closePath();
      }

      canvas.beginPath();
      canvas.rect(positionX,positionY,barWidthPercent * barWidth,barHeight);
      canvas.fillStyle = 'rgb(' + colors[j] + ')';
      canvas.fill();
      positionY += barHeight + barSpacing
      canvas.closePath();

      if(j == 3){

        j = 0;
      }else{

        j++;
      }
    }
}
