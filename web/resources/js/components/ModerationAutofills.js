/*
	Copyright © XlXi 2023
*/

import { Component } from 'react';

const autofills = [
	{ Label: 'Account Theft', Autofill: 'This account has been closed as compromised.' },
	{ Label: 'Requested Deletion', Autofill: 'Your account has been deleted as per your request. Thank you for being a part of the VirtuBrick community.' },
	{ Label: 'Spam', Autofill: 'Do not repeatedly post or spam chat or content in VirtuBrick.' },
	{ Label: 'Swear', Autofill: 'Do not swear, use profanity or otherwise say inappropriate things in VirtuBrick.' },
	{ Label: 'Personal Info', Autofill: 'Do not ask for or give out personal, real-life, or private information on VirtuBrick.' },
	{ Label: 'Spam Alt', Autofill: 'Do not create accounts just for the purpose of breaking the rules.' },
	{ Label: 'Dating', Autofill: 'Dating, Sexting, or other inappropriate behavior is not acceptable on VirtuBrick.' },
	{ Label: 'Inappropriate Talk', Autofill: 'This content is not appropriate for VirtuBrick. Do not chat, post, or otherwise discuss inappropriate topics on VirtuBrick.' },
	{ Label: 'Link', Autofill: 'The only links you are allowed to post on VirtuBrick are virtubrick.net links, youtube.com links, twitter.com links, and twitch.tv links. No other links are allowed. Posting any other links will result in further moderation actions.' },
	{ Label: 'Harassment', Autofill: 'Do not harass other users. Do not say inappropriate or mean things about others on VirtuBrick.' },
	{ Label: 'Scam', Autofill: 'Scamming is a violation of the Terms of Service. Do not continue to scam on VirtuBrick.' },
	{ Label: 'Bad Image', Autofill: 'This image is not appropriate for VirtuBrick. Please review our rules and upload only appropriate content.' }
];

class ModerationAutofills extends Component {
	constructor(props) {
		super(props);
	}
	
	autofill(autofillText) {
		document.getElementById('user-note').value = autofillText;
	}
	
	render() {
		return (
			<>
			{
				autofills.map(({Label, Autofill}) =>
					<button type="button" className="btn btn-sm btn-secondary d-block mb-1" onClick={ () => this.autofill(Autofill) }>{ Label }</button>
				)
			}
			</>
		);
	}
}

export default ModerationAutofills;