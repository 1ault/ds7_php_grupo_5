export const assets = {
    load: {
        async fondo({ gameData, name })
        {
            const response = await fetch("/api/load_fondo.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify(name)
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }

            const data = await response.json();
            const image = new Image();

            image.onload = () => {
                gameData.context.drawImage(image, 0, 0);
            }

            image.src = data.sprite;
        },
        async personaje({ gameData, name })
        {
            const response = await fetch("/api/load_personaje.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify({
                    "name": name
                })
            });

            let data;

            try {
                data = await response.json();
            } catch (e) {
                console.error(e);
                throw new Error(`Error: invalid json response`);
            }

            if (!response.ok || data.error) {
                throw new Error(`Error: ${data.error}`);
            }


            const image = new Image();
            image.onload = () => {
                gameData.context.drawImage
                (
                    image, 
                    data.position_x, 
                    data.position_y
                );
            }
            image.src = data.sprite;
        }
  }
};
