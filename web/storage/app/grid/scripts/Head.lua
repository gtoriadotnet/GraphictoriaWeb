local assetUrl, fileExtension, x, y, baseUrl, rigUrl = ...

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("ScriptContext").ScriptsDisabled = true 
settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;

local Lighting = game:GetService("Lighting")
Lighting.ClockTime = 13
Lighting.GeographicLatitude = -5

local mannequin = game:GetObjects(rigUrl)[1]
mannequin.Humanoid.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.None
mannequin.Parent = workspace

local object = game:GetObjects(assetUrl)[1]

mannequin.Head.BrickColor = BrickColor.Gray()
if mannequin.Head:FindFirstChild("Mesh") then
	mannequin.Head.Mesh:Destroy()
end
object.Parent = mannequin.Head

for _, child in pairs(mannequin:GetChildren()) do
	if child:IsA("BasePart") and child.Name ~= "Head" then
		child:Destroy()
	end
end


return game:GetService("ThumbnailGenerator"):Click(fileExtension, x, y, --[[hideSky = ]] true)