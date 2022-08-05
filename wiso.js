var questionBank= [
    {
        question : 'Was bedeutet GmbH?',
        option : ['Aktiengesellschaft','Offene Handelsgesellschaft','Kommanditgesellschaft','Gesellschaft mit beschränkter Haftung'],
        answer : 'Gesellschaft mit beschränkter Haftung'
    },
    {
        question : 'Herr H. hat mit der Müller GmbH einen schriftlichen Arbeitsvertrag geschlossen. Welcher bestandteil bindet die GmbH an kollektives Arbeitsrecht?',
        option : ['Er bekommt einen Dienstwagen','Er arbeitet im Kundenservice','Tarifliche Arbeitszeit 38,5 Std.','Fahrtkostenzuschuß'],
        answer : 'Tarifliche Arbeitszeit 38,5 Std.'
    },
    {
        question : 'Müller GmbH hat Maier GmbH gekauf und betreibt diese unter beibehaltung der Maier GmbH weiter. Um welche form von Unternehmenszusammenschluß handelt es sich?',
        option : ['Fussion','Arbeitsgemeinschaft','Interessengemeinschaft','Konzern'],
        answer : 'Konzern'
    },
    {
        question : 'Für die Dienstleistung der Maier Gmbh gibt es viele Konkurrenten. Der Kundenkreis ist sehr Umpfangreich. Welcher begriff beschreibt diese Marktform?',
        option : ['Nachfragemonopol','Polypol','Angebotsmonopol','Nachfrageoligopol'],
        answer : 'Polypol'
    },
    {
        question : 'Was bedeutet Bedarf',
        option : ['Bedürfnis+Kaufkraft','Angebote','Nachfrage','Pleite'],
        answer : 'Bedürfnis+Kaufkraft'
    }
]

var question= document.getElementById('question');
var quizContainer= document.getElementById('quiz-container');
var scorecard= document.getElementById('scorecard');
var option0= document.getElementById('option0');
var option1= document.getElementById('option1');
var option2= document.getElementById('option2');
var option3= document.getElementById('option3');
var next= document.querySelector('.next');
var points= document.getElementById('score');
var span= document.querySelectorAll('span');
var i=0;
var score= 0;

//function to display questions
function displayQuestion(){
    for(var a=0;a<span.length;a++){
        span[a].style.background='none';
    }
    question.innerHTML= 'Q.'+(i+1)+' '+questionBank[i].question;
    option0.innerHTML= questionBank[i].option[0];
    option1.innerHTML= questionBank[i].option[1];
    option2.innerHTML= questionBank[i].option[2];
    option3.innerHTML= questionBank[i].option[3];
    stat.innerHTML= 'Antwort'+' '+(i+1)+' '+'von'+' '+questionBank.length;
}

//function to calculate scores
function calcScore(e){
    if(e.innerHTML===questionBank[i].answer && score<questionBank.length)
    {
        score= score+1;
        document.getElementById(e.id).style.background= 'limegreen';
    }
    else{
        document.getElementById(e.id).style.background= 'black';
    }
    setTimeout(nextQuestion,300);
}

//function to display next question
function nextQuestion(){
    if(i<questionBank.length-1)
    {
        i=i+1;
        displayQuestion();
    }
    else{
        points.innerHTML= score+ '/'+ questionBank.length;
        quizContainer.style.display= 'none';
        scoreboard.style.display= 'block';
    }
}

//click events to next button
next.addEventListener('click',nextQuestion);

//Back to Quiz button event
function backToQuiz(){
    location.reload();
}

//function to check Answers
function checkAnswer(){
    var answerBank= document.getElementById('answerBank');
    var answers= document.getElementById('answers');
    answerBank.style.display= 'block';
    scoreboard.style.display='none';
    for(var a=0;a<questionBank.length;a++)
    {
        var list= document.createElement('li');
        list.innerHTML= questionBank[a].answer;
        answers.appendChild(list);
    }
}


displayQuestion(); 