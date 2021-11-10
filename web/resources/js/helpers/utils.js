// © XlXi 2021
// Graphictoria 5

import { useEffect } from 'react';

function useOnClickOutside(refs, handler) {
	useEffect(
		() => {
			const listener = (event) => {
				var shouldReturn = false;
				
				refs.map(
					function(val)
					{
						if (shouldReturn == false && (!val.current || val.current.contains(event.target))) 
						{
							shouldReturn = true;
						}
					}
				);
				
				if(shouldReturn)
				{
					return;
				}
				
				handler(event);
			};
			document.addEventListener('mousedown', listener);
			document.addEventListener('touchstart', listener);
			return () => {
				document.removeEventListener('mousedown', listener);
				document.removeEventListener('touchstart', listener);
			};
		},
		[refs, handler]
	);
}

export {
	useOnClickOutside
};