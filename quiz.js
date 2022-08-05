var questionBank= [
    {
        question : 'Wann werden Änderungen in Gruppenmitgliedschaften bei Benutzern aktiv?',
        option : ['Bei der naechsten Anmeldung','Beim Abmelden','Bei neu Start','Beim Umfallen'],
        answer : 'Bei der naechsten Anmeldung'
    },
    {
        question : 'Welche Komponenten gibt es im Active Directory?',
        option : ['Domaenen','Festplatten','Computer','Tablets'],
        answer : 'Domaenen'
    },
    {
        question : 'Welche Informationen brauche ich um auf einem DHCP-Server eine Reservierung vorzunehmen?',
        option : ['Hausnummer','MAC Adresse','DHCP','Kabelanschluß'],
        answer : 'MAC Adresse'
    },
    {
        question : 'Welche der folgenden Benutzernamen funktionieren bei der Windows Anmeldung?',
        option : ['contoso.com/Benutzer','contoso\Benutzer','Benutzer@User','User@Benutzer'],
        answer : 'contoso\Benutzer'
    },
    {
        question : 'Mit welchem Tool kann ich Betriebsmaster in einem AD zuweisen oder zwangsübernehmen',
        option : ['DHCP','C++','Hammer','PowerShell'],
        answer : 'PowerShell'
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