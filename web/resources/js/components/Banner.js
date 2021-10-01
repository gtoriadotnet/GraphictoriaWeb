// © XlXi 2021
// Graphictoria 5

const Banner = (props) => {
	let description = props.description;
	let type = props.type;
	
	let CloseButton;
	
	if(props.dismissible)
	{
		CloseButton = <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>;
	}
	
	return (
		<div className={`alert${props.dismissible === true || props.dismissible === 'true' ? ' alert-dismissible' : ''} graphictoria-alert alert-${type}`}>
			<p className="mb-0">{description}</p>
			{CloseButton}
		</div>
	);
};

export default Banner;