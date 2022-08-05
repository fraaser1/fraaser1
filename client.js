var questionBank= [
    {
        question : 'Welche Computer-Reparaturoptionen gibt es?',
        option : ['Bei der nächsten Anmeldung Booten','Beim Abmelden Stecker ziehen','Systemimage 		     	Wiederherstellung','Alle Passwörter Löschen'],
        answer : 'Systemimage Wiederherstellung'
    },
    {
        question : 'Welche Infos liefert ipconfig nicht?',
        option : ['IP4-Adresse','IP6-Adresse','das Passwort','Standard-Gateway'],
        answer : 'das Passwort'
    },
    {
        question : 'Was ist kein DNS Record-Typ ?',
        option : ['B','AAAA','NS','CNAME'],
        answer : 'B'
    },
    {
        question : 'Welche Registry-Hives kennen Sie?',
        option : ['HKEY_CURRENT_USER','HKEY_LOCAL_MATCHO','HKEY_ADMINISTRATOR','HKEY_CLASSES_BOOT'],
        answer : 'HKEY_CURRENT_USER'
    },
    {
        question : 'Tools zur Überwachung der Windows-Ressourcen?',
        option : ['USB-Stick','C++','Ressourcenmonitor','Powerpoint'],
        answer : 'Ressourcenmonitor'
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