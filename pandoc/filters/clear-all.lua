function Span(span)
  local function alwaysTrue() return true end
if span.classes:includes('personaldata') then
  local value, position = span.classes:find('personaldata')
  span.classes:remove(position)
  while span.classes:find_if(alwaysTrue, 1) do
    span.classes:remove(1)
  end
end
return span
end