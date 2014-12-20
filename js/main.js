function selectToVote (selection, group) {
	//1 =  Team Rocket vs Ash
	//2 =  Starters

	if (group == 1){
		document.getElementById("choiceTeam").value = selection;
	}else{
		document.getElementById("voteStarter").value = selection;
	}
}

function selectVoteTeamAsh(selection){
	console.log("selectVoteTeamAsh");
	getElement("rocket").style.opacity = "0.5";
	getElement("ash").style.opacity = "0.5";

	getElement(selection).style.opacity = "1";

}

function selectVoteStarter(selection){
	console.log("selectVoteStarter");
	getElement("charmander").style.opacity = "0.5";
	getElement("squirtle").style.opacity = "0.5";
	getElement("bulbasaur").style.opacity = "0.5";
	

	getElement(selection).style.opacity = "1";

}

function getElement(id){
	return document.getElementById(id);
}